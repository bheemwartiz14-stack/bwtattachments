<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'country',
    'iso_code',
    'standard_vat_rate',
    'eu_status',
    'currency',
    'b2b_reverse_charge',
])]

class VatRate extends Model
{
    use HasUuids;

    protected $casts = [
        'standard_vat_rate' => 'decimal:2',
        'b2b_reverse_charge' => 'boolean',
    ];
}
