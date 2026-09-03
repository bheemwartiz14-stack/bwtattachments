<?php

declare(strict_types=1);

namespace App\Models;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'order_from_user_id',
    'order_to_user_id',
    'order_number',
    'order_date',
    'notes',
    'order_email_message',
    'delivery_country',
    'sub_total',
    'vat_percentage',
    'vat_amount',
    'grand_total',
    'order_reference',
    'pdf_file',
    'status',
    'show_logo_on_pdf',
])]
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, HasUuids;

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'sub_total' => 'decimal:2',
            'vat_percentage' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'status' => OrderStatus::class,
        ];
    }
    public function items(): HasMany
    {
        return $this->hasMany(OrderItems::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'order_from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'order_to_user_id');
    }

    // Backward compat: many views/services use $order->user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'order_from_user_id');
    }
}
