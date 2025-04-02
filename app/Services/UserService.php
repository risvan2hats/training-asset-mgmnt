<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * User Management Service
 * 
 * Handles all business logic for user management including:
 * - User CRUD operations
 * - Country-based access control
 * - Password management
 * - User-related asset and history queries
 */
class UserService extends FilterService
{
    // Filter configuration for user queries
    protected array $userFilterMap = [
        'search' => [
            'fields'    => ['first_name', 'last_name', 'email', 'employee_id'], 
            'operator'  => 'search'
        ],
        'country_code'  => 'country_code',
        'role'          => 'role'
    ];

    public function __construct(protected UserRepository $userRepository) {}

    /**
     * Create a new user with proper authorization and password hashing
     * 
     * @param array $data User data
     * @param User $currentUser Performing user
     * @return mixed Created user
     */
    public function createUser(array $data, User $currentUser)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    /**
     * Get user with access control
     * 
     * @param int $id User ID
     * @param User $currentUser Requesting user
     * @return mixed User data
     * @throws AuthorizationException
     */
    public function getUser($id, User $currentUser)
    {
        $user = $this->userRepository->find($id);
        $this->validateAccess($currentUser, $user);
        return $user;
    }

    /**
     * Update user with access control and password management
     * 
     * @param int $id User ID
     * @param array $data Update data
     * @param User $currentUser Performing user
     * @return mixed Updated user
     * @throws AuthorizationException
     */
    public function updateUser($id, array $data, User $currentUser)
    {
        $user = $this->userRepository->find($id);
        $this->validateAccess($currentUser, $user);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepository->update($data, $id);
    }

    /**
     * Delete user with access control and super admin protection
     * 
     * @param int $id User ID
     * @param User $currentUser Performing user
     * @return mixed Deletion result
     * @throws AuthorizationException
     */
    public function deleteUser($id, User $currentUser)
    {
        $user = $this->userRepository->find($id);
        $this->validateAccess($currentUser, $user);

        if ($user->isSuperAdmin()) {
            abort(403, 'Cannot delete super admin.');
        }

        return $this->userRepository->delete($id);
    }

    /**
     * Get paginated users with filters
     * 
     * @param User $currentUser Requesting user
     * @param array $filters Filter criteria
     * @return LengthAwarePaginator
     */
    public function getAllUsers(User $currentUser, array $filters = []): LengthAwarePaginator
    {
        $query = $this->buildBaseQuery($currentUser);
        $this->applyFilters($query, $filters, $this->userFilterMap);
        return $query->orderBy('last_name')
                    ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get paginated assets assigned to user
     * 
     * @param int $userId User ID
     * @param User $currentUser Requesting user
     * @return LengthAwarePaginator
     * @throws AuthorizationException
     */
    public function getUserAssets($userId, User $currentUser): LengthAwarePaginator
    {
        $user = $this->getUser($userId, $currentUser);
        return $user->assets()->paginate(10);
    }

    /**
     * Get paginated asset histories for user
     * 
     * @param int $userId User ID
     * @param User $currentUser Requesting user
     * @return LengthAwarePaginator
     * @throws AuthorizationException
     */
    public function getUserHistories($userId, User $currentUser): LengthAwarePaginator
    {
        $user = $this->getUser($userId, $currentUser);
        return $user->assetHistories()
            ->with('asset')
            ->latest()
            ->paginate(10);
    }

    /**
     * Build base user query with authorization
     * 
     * @param User $currentUser Requesting user
     * @return Builder
     */
    protected function buildBaseQuery(User $currentUser): Builder
    {
        $query = $this->userRepository->newQuery();

        if (!$currentUser->isSuperAdmin()) {
            $query->where('country_code', $currentUser->country_code);
        }

        return $query;
    }

    /**
     * Validate user access to another user record
     * 
     * @param User $currentUser Requesting user
     * @param User $targetUser User being accessed
     * @throws AuthorizationException
     */
    protected function validateAccess(User $currentUser, User $targetUser): void
    {
        if (!$currentUser->isSuperAdmin() && $targetUser->country_code !== $currentUser->country_code) {
            abort(403, 'Unauthorized action.');
        }
    }
}