<?php

namespace App\Repositories;

use App\Models\Asset;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

class AssetRepository extends BaseRepository
{
    public function model()
    {
        return Asset::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}