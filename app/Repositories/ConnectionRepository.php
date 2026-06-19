<?php

namespace App\Repositories;

use App\Models\Connection;
use App\Repositories\Interfaces\ConnectionRepositoryInterface;

class ConnectionRepository extends BaseRepository implements ConnectionRepositoryInterface
{
    public function __construct(Connection $model)
    {
        parent::__construct($model);
    }
}
