<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repositories\AssetRepository;
use App\Repositories\LocationRepository;
use App\Repositories\AssetHistoryRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AssetService extends FilterService
{
    // Field mapping for asset filtering
    protected array $assetFilterMap = [
        'asset_type'    => 'asset_type',
        'location_id'   => 'location_id',
        'status'        => 'status',
        'search'        => ['fields' => ['serial_no', 'asset_type', 'hardware_standard'], 'operator' => 'search'],
        'asset_ids'     => ['field' => 'id', 'operator' => 'in']
    ];

    public function __construct(
        protected AssetRepository $assetRepository,
        protected LocationRepository $locationRepository,
        protected AssetHistoryRepository $assetHistoryRepository
    ) {}

    /**
     * Create new asset with audit history
     */
    public function createAsset(array $data, User $user)
    {
        // $this->validateAccess($user, null, $data['country_code'] ?? null);

        return DB::transaction(function () use ($data, $user) {
            $asset = $this->assetRepository->create($data);

            $this->logHistory(
                $asset->id,
                $user->id,
                'created',
                $this->generateCreationNote($user, $asset),
                null,
                $asset->toArray(),
                $data['notes'] ?? null
            );

            return $asset;
        });
    }

    /**
     * Update asset with change tracking
     */
    public function updateAsset($id, array $data, User $user)
    {
        $asset = $this->assetRepository->find($id);
        // $this->validateAccess($user, $asset);

        return DB::transaction(function () use ($id, $data, $user, $asset) {
            $oldData        = $asset->toArray();
            $updatedAsset   = $this->assetRepository->update($data, $id);
            $newData        = $updatedAsset->fresh()->toArray();

            $this->logHistory(
                $id,
                $user->id,
                'updated',
                $this->generateUpdateNote($oldData, $newData),
                $oldData,
                $newData,
                $data['notes'] ?? null
            );

            return $updatedAsset;
        });
    }

    /**
     * Soft delete asset with audit trail
     */
    public function deleteAsset($id, User $user)
    {
        $asset = $this->assetRepository->find($id);
        // $this->validateAccess($user, $asset);

        return DB::transaction(function () use ($id, $user, $asset) {
            $this->logHistory(
                $id,
                $user->id,
                'deleted',
                $this->generateDeletionNote($user, $asset),
                $asset->toArray(),
                ['status' => 'Deleted'],
                'Asset marked as deleted in system'
            );

            return $this->assetRepository->update(['status' => 'Deleted'], $id);
        });
    }

    /**
     * Move asset between locations with tracking
     */
    public function moveAsset($assetId, $request, User $user)
    {
        $asset = $this->assetRepository->find($assetId);
        $newLocation = $this->locationRepository->find($request->location_id);
        
        // $this->validateAccess($user, $asset);
        // $this->validateAccess($user, $newLocation);

        return DB::transaction(function () use ($assetId, $request, $user, $asset, $newLocation) {
            $oldLocation    = $this->locationRepository->find($asset->location_id);
            $updatedAsset   = $this->assetRepository->update(['location_id' => $newLocation->id], $assetId);

            $this->logHistory(
                $assetId,
                $user->id,
                'moved',
                $this->generateMoveNote($oldLocation, $newLocation, $request->notes),
                $asset->toArray(),
                $updatedAsset->fresh()->toArray(),
                $request->notes,
                [
                    'from_location_id' => $oldLocation->id,
                    'to_location_id' => $newLocation->id
                ]
            );

            return $updatedAsset;
        });
    }

    /**
     * Get paginated assets with filters
     */
    public function getAllAssets(User $user, array $filters = [], bool $skipPagination = false): LengthAwarePaginator|Collection
    {
        $query = $this->buildAssetQuery($user)
            ->with(['location', 'assignedUser']);
    
        $this->applyDateFilters($query, $filters);
    
        return $skipPagination 
            ? $query->get()
            : $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get single asset with full history
     */
    public function getAssetWithHistories($id, User $user)
    {
        $asset = $this->assetRepository->with(['location', 'assignedUser', 'histories.user', 'histories.fromLocation', 'histories.toLocation'])->find($id);
        // $this->validateAccess($user, $asset);
        return $asset;
    }

    /**
     * Get locations accessible to user
     */
    public function getLocationsForUser(User $user)
    {
        return $this->buildLocationQuery($user)->orderBy('name')->get();
    }

    /**
     * Get paginated asset history
     */
    public function getAssetHistories($assetId, User $user, $filters = []): LengthAwarePaginator
    {
        $asset = $this->assetRepository->find($assetId);
        // $this->validateAccess($user, $asset);

        return $this->buildHistoryQuery($assetId, $filters)->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get all histories with filters
     */
    public function getAllHistories(User $user, array $filters = []): LengthAwarePaginator
    {
        return $this->buildHistoryQuery(null, $filters, $user)->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get recent activities for dashboard
     */
    public function getRecentActivities(User $user, int $limit = 5)
    {
        return $this->buildHistoryQuery(null, [], $user)->limit($limit)->get();
    }

    /**
     * Base asset query with access control
     */
    protected function buildAssetQuery(User $user): Builder
    {
        $query = $this->assetRepository->newQuery();

        // if (!$user->isSuperAdmin()) {
        //     $query->where('country_code', $user->country_code);
        // }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Base location query with access control
     */
    protected function buildLocationQuery(User $user): Builder
    {
        $query = $this->locationRepository->newQuery();

        // if (!$user->isSuperAdmin()) {
        //     $query->where('country_code', $user->country_code);
        // }

        return $query;
    }

    /**
     * History query builder with optional filters
     */
    protected function buildHistoryQuery(?int $assetId, array $filters, ?User $user = null): Builder
    {
        // Get the Eloquent Builder instance directly
        $query = $this->assetHistoryRepository->newQuery()
            ->with(['asset', 'user', 'fromLocation', 'toLocation']);
        
        if ($assetId) {
            $query->where('asset_id', $assetId);
        }
        
        // if ($user && !$user->isSuperAdmin()) {
        //     $query->whereHas('asset', fn($q) => $q->where('country_code', $user->country_code));
        // }
        
        $this->applyDateFilters($query, $filters);
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Record asset history entry
     */
    protected function logHistory(
        int $assetId,
        int $userId,
        string $action,
        string $actionNotes,
        ?array $oldData = null,
        ?array $newData = null,
        ?string $notes = null,
        array $additionalData = []
    ) {
        $historyData = array_merge([
            'asset_id'      => $assetId,
            'user_id'       => $userId,
            'action'        => $action,
            'action_notes'  => $actionNotes,
            'old_data'      => $oldData,
            'new_data'      => $newData,
            'notes'         => $notes
        ], $additionalData);

        return $this->assetHistoryRepository->create($historyData);
    }

    /**
     * Generate creation note for audit log
     */
    protected function generateCreationNote(User $user, $asset): string
    {
        return sprintf('%s created %s %s (Serial: %s)',
            $user->name,
            $asset->hardware_standard ?? '',
            $asset->asset_type,
            $asset->serial_no
        );
    }

    /**
     * Generate update note with changed fields
     */
    protected function generateUpdateNote(array $oldData, array $newData): string
    {
        $changedFields = $this->getChangedFields($oldData, $newData);

        if (empty($changedFields)) {
            return 'No fields updated (only notes added)';
        }

        $changes = array_map(
            fn($field) => sprintf('%s from "%s" to "%s"',
                $this->getFieldLabel($field),
                $this->formatFieldValue($oldData[$field] ?? ''),
                $this->formatFieldValue($newData[$field] ?? '')
            ),
            $changedFields
        );

        return 'Updated ' . $this->joinChangesWithGrammar($changes);
    }

    /**
     * Generate deletion note for audit log
     */
    protected function generateDeletionNote(User $user, $asset): string
    {
        return sprintf('%s deleted asset %s (%s)',
            $user->name,
            $asset->serial_no,
            $asset->asset_type
        );
    }

    /**
     * Generate move note with location details
     */
    protected function generateMoveNote($fromLocation, $toLocation, ?string $notes): string
    {
        $note = sprintf('Moved asset from "%s" to "%s"',
            $fromLocation->name,
            $toLocation->name
        );
        return $notes ? "$note. Notes: " . $this->formatFieldValue($notes) : $note;
    }

    /**
     * Get array of changed field names
     */
    protected function getChangedFields(array $oldData, array $newData): array
    {
        $changedFields = [];
        foreach ($oldData as $key => $value) {
            if (isset($newData[$key]) && $value != $newData[$key]) {
                $changedFields[] = $key;
            }
        }
        return array_diff($changedFields, ['updated_at', 'remember_token']);
    }

    /**
     * Format field value for display
     */
    protected function formatFieldValue($value): string
    {
        return is_array($value) || is_object($value) 
            ? json_encode($value) 
            : Str::limit(strval($value), 50);
    }

    /**
     * Convert field name to readable label
     */
    protected function getFieldLabel(string $field): string
    {
        return str_replace('_', ' ', Str::snake($field));
    }

    /**
     * Join change descriptions with proper grammar
     */
    protected function joinChangesWithGrammar(array $changes): string
    {
        return count($changes) === 1 
            ? $changes[0] 
            : implode(', ', array_slice($changes, 0, -1)) . ', and ' . end($changes);
    }

    /**
     * Validate user access to resource
     */
    // protected function validateAccess(User $user, $resource = null, ?string $countryCode = null): void
    // {
    //     if ($user->isSuperAdmin()) return;

    //     $resourceCountry = $resource->country_code ?? null;
    //     if (($countryCode ?? $resourceCountry) !== $user->country_code) {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }
}