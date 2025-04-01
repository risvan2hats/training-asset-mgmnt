<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class FilterService
{
    /**
     * Apply common filters to the query
     */
    protected function applyFilters(Builder $query, array $filters, array $filterMap): Builder
    {
        foreach ($filterMap as $filterKey => $mapping) {
            if (!empty($filters[$filterKey])) {
                $this->applyFilter($query, $mapping, $filters[$filterKey]);
            }
        }
        return $query;
    }

    /**
     * Apply a single filter based on mapping configuration
     */
    protected function applyFilter(Builder $query, array|string $mapping, $value): void
    {
        // Simple field mapping (string format)
        if (is_string($mapping)) {
            $query->where($mapping, $value);
            return;
        }

        // Complex mapping (array format)
        if (isset($mapping['field']) && isset($mapping['operator'])) {
            // New style mapping: ['field' => 'name', 'operator' => 'like']
            $field = $mapping['field'];
            $operator = $mapping['operator'];
        } elseif (count($mapping) >= 2) {
            // Legacy style mapping: ['name', 'like']
            $field = $mapping[0];
            $operator = $mapping[1];
        } else {
            throw new \InvalidArgumentException('Invalid filter mapping configuration');
        }

        // Apply the filter based on operator type
        switch ($operator) {
            case 'like':
                $query->where($field, 'like', "%{$value}%");
                break;
            case 'in':
                $query->whereIn($field, (array)$value);
                break;
            case 'search':
                // Handle search across multiple fields
                if (isset($mapping['fields'])) {
                    $query->where(function ($q) use ($mapping, $value) {
                        foreach ($mapping['fields'] as $searchField) {
                            $q->orWhere($searchField, 'like', "%{$value}%");
                        }
                    });
                } else {
                    $query->where($field, 'like', "%{$value}%");
                }
                break;
            default:
                $query->where($field, $operator, $value);
        }
    }

    /**
     * Apply date range filters
     */
    protected function applyDateFilters(Builder $query, array $filters, string $field = 'created_at'): Builder
    {
        // Handle date_from filter
        if (!empty($filters['date_from']) && $this->isValidDate($filters['date_from'])) {
            $query->whereDate($field, '>=', $filters['date_from']);
        }

        // Handle date_to filter
        if (!empty($filters['date_to']) && $this->isValidDate($filters['date_to'])) {
            $query->whereDate($field, '<=', $filters['date_to']);
        }

        return $query;
    }

    /**
     * Validate date string format (YYYY-MM-DD)
     */
    protected function isValidDate(?string $date): bool
    {
        if (empty($date)) {
            return false;
        }

        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Apply search across multiple fields
     */
    protected function applySearch(Builder $query, ?string $searchTerm, array $searchableFields): Builder
    {
        if (empty($searchTerm)) {
            return $query;
        }

        return $query->where(function ($q) use ($searchTerm, $searchableFields) {
            foreach ($searchableFields as $field) {
                $q->orWhere($field, 'like', "%{$searchTerm}%");
            }
        });
    }

    /**
     * Apply pagination
     */
    protected function applyPagination(Builder $query, array $filters): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 15;
        return $query->paginate($perPage);
    }
}