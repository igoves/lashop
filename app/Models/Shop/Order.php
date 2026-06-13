<?php

namespace App\Models\Shop;

use App\Models\User;
use Database\Factories\Shop\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $table = 'shop_orders';

    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'comment', 'total', 'status',
        'delivery_method', 'payment_method', 'legacy_order',
        'created_at', 'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
