<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'reseller_id', 'quotation_number', 'margin_percentage', 'pdf_file', 'status', 'notes', 'contact_name', 'contact_email', 'contact_phone', 'valid_until', 'issue_date'])]
class Quotation extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected function casts(): array
    {
        return [
            'margin_percentage' => 'decimal:2',
            'valid_until' => 'date',
            'issue_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
