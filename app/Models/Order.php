<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = "Order";

    protected $fillable = [
        "order_id",
        "user_id",
        "total_amount",
        "status",
        "payment_status",
        "payment_reference",
        "shipping_address",
    ];

    protected $casts = [
        "total_amount" => "decimal:2",
        "shipping_address" => "array",
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, "order_id", "order_id");
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            "pending" => "warning",
            "processing" => "primary",
            "shipped" => "info",
            "delivered" => "success",
            "cancelled" => "danger",
            default => "secondary",
        };
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match ($this->payment_status) {
            "pending" => "warning",
            "paid" => "success",
            "failed" => "danger",
            default => "secondary",
        };
    }
}
