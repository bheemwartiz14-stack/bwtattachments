<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerApplication extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'address',
        'postal_code',
        'place',
        'country',
        'telephone',
        'email',
        'website',
    ];
}
