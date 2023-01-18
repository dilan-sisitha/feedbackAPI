<?php

namespace App\Repositories;

use App\Interfaces\SiteDataRepositoryInterface;
use App\Models\SiteData;
use Illuminate\Database\Eloquent\Model;

class SiteDataRepository extends BaseRepository implements SiteDataRepositoryInterface
{
    public function __construct(SiteData $model)
    {
        parent::__construct($model);
    }


}
