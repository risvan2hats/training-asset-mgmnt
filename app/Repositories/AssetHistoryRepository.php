<?php

namespace App\Repositories;

use App\Models\AssetHistory;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class AssetHistoryRepository extends BaseRepository
{
    public function model()
    {
        return AssetHistory::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}