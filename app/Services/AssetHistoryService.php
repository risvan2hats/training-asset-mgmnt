<?php

namespace App\Services;

use App\Repositories\AssetHistoryRepository;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class AssetHistoryService extends FilterService
{
    protected array $historyFilterMap = [
        'asset_id' => 'asset_id',
        'asset_ids' => ['field' => 'asset_id', 'operator' => 'in'],
        'action' => 'action',
        'user_id' => 'user_id'
    ];

    public function __construct(
        protected AssetHistoryRepository $assetHistoryRepository
    ) {}

    /**
     * Get paginated history for a specific asset
     */
    public function getAssetHistories(int $assetId, User $user, array $filters = []): LengthAwarePaginator
    {
        $query = $this->buildBaseQuery($user, ['asset_id' => $assetId])->with(['user', 'fromLocation', 'toLocation']);
        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Get all paginated asset histories with filters
     */
    public function getAllHistories(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = $this->buildBaseQuery($user)->with(['asset', 'user', 'fromLocation', 'toLocation']);
        return $this->applyCommonFilters($query, $filters);
    }

    /**
     * Create a new history record
     */
    public function createHistory(array $data): mixed
    {
        return $this->assetHistoryRepository->create($data);
    }

    /**
     * Build base query with authorization and common conditions
     */
    protected function buildBaseQuery(User $user, array $conditions = []): Builder
    {
        $query = $this->assetHistoryRepository->newQuery();

        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }

        // if (!$user->isSuperAdmin()) {
        //     $query->whereHas('asset', fn($q) => $q->where('country_code', $user->country_code));
        // }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Apply common filters and pagination
     */
    protected function applyCommonFilters(Builder $query, array $filters): LengthAwarePaginator
    {
        $this->applyFilters($query, $filters, $this->historyFilterMap);
        $this->applyDateFilters($query, $filters);
        
        return $this->applyPagination($query, $filters);
    }
}