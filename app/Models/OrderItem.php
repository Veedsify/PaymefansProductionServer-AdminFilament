<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = "OrderItem";

    protected $fillable = [
        "order_id",
        "product_id",
        "quantity",
        "price",
        "size_id",
    ];

    protected $casts = [
        "quantity" => "integer",
        "price" => "decimal:2",
        "size_id" => "integer",
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id", "order_id");
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id", "product_id");
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(ProductSize::class, "size_id");
    }

    public function getTotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}
