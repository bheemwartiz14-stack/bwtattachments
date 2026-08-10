<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncTombstone extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}
