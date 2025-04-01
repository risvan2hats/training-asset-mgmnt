<?php

namespace App\Repositories;

use App\Models\Location;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

class LocationRepository extends BaseRepository
{
    public function model()
    {
        return Location::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}