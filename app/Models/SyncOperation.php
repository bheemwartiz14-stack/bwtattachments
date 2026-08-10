<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncOperation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => 'integer',
    ];
}
