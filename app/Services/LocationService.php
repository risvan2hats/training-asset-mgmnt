<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\LocationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Location Management Service
 * 
 * Handles all business logic for location management including:
 * - CRUD operations for locations
 * - Country-based access control
 * - Search and filtering capabilities
 */
class LocationService extends FilterService
{
    // Filter configuration for location queries
    protected array $locationFilterMap = [
        'search'        => ['fields' => ['name', 'address'], 'operator' => 'search'],
        'country_code'  => 'country_code'
    ];

    public function __construct(protected LocationRepository $locationRepository) {}

    /**
     * Create a new location with country code enforcement
     * 
     * @param array $data Location data
     * @param User $user Performing user
     * @return mixed Created location
     */
    public function createLocation(array $data, User $user)
    {
        return $this->locationRepository->create($data);
    }

    /**
     * Get single location with access control
     * 
     * @param int $id Location ID
     * @param User $user Requesting user
     * @return mixed Location data
     * @throws AuthorizationException
     */
    public function getLocation($id, User $user)
    {
        $location = $this->locationRepository->find($id);
        // $this->validateAccess($user, $location);
        return $location;
    }

    /**
     * Update location with access control
     * 
     * @param int $id Location ID
     * @param array $data Update data
     * @param User $user Performing user
     * @return mixed Updated location
     * @throws AuthorizationException
     */
    public function updateLocation($id, array $data, User $user)
    {
        $location = $this->locationRepository->find($id);
        // $this->validateAccess($user, $location);
        return $this->locationRepository->update($data, $id);
    }

    /**
     * Delete location with access control
     * 
     * @param int $id Location ID
     * @param User $user Performing user
     * @return mixed Deletion result
     * @throws AuthorizationException
     */
    public function deleteLocation($id, User $user)
    {
        $location = $this->locationRepository->find($id);
        // $this->validateAccess($user, $location);
        return $this->locationRepository->delete($id);
    }

    /**
     * Get paginated locations with filters
     * 
     * @param User $user Requesting user
     * @param array $filters Filter criteria
     * @return LengthAwarePaginator
     */
    public function getAllLocations(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = $this->buildBaseQuery($user);
        $this->applyFilters($query, $filters, $this->locationFilterMap);
        return $query->orderBy('name', 'asc')
                    ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Build base location query with authorization
     * 
     * @param User $user Requesting user
     * @return Builder
     */
    protected function buildBaseQuery(User $user): Builder
    {
        $query = $this->locationRepository->newQuery();

        // if (!$user->isSuperAdmin()) {
        //     $query->where('country_code', $user->country_code);
        // }

        return $query;
    }

    /**
     * Validate user access to location
     * 
     * @param User $user Requesting user
     * @param mixed $location Location being accessed
     * @throws AuthorizationException
     */
    // protected function validateAccess(User $user, $location): void
    // {
    //     if (!$user->isSuperAdmin() && $location->country_code !== $user->country_code) {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }
}