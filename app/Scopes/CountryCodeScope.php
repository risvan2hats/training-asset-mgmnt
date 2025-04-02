<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CountryCodeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // Skip if no authenticated user or if super admin
        if (!Auth::check() || Auth::user()->isSuperAdmin()) {
            return;
        }

        $user   = Auth::user();
        $table  = $model->getTable();

        // Check if table has country_code column
        if (Schema::hasColumn($table, 'country_code')) {
            $builder->where($table . '.country_code', $user->country_code);
        }
        // For models that need to scope through asset relationship
        elseif (method_exists($model, 'asset')) {
            $builder->whereHas('asset', function ($query) use ($user) {
                $query->where('country_code', $user->country_code);
            });
        }
    }
}