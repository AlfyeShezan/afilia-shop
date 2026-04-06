<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'subtotal', 'tax', 
        'shipping_cost', 'discount', 'total', 'currency', 
        'exchange_rate', 'status', 'shipping_name', 
        'points_earned', 'points_redeemed', 'points_discount',
        'shipping_email', 'shipping_phone', 'shipping_address', 
        'shipping_city', 'shipping_state', 'shipping_zip', 
        'shipping_country', 'shipping_method', 'tracking_number', 
        'billing_same_as_shipping', 'billing_address', 
        'payment_method', 'payment_status', 'snap_token', 'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'points_discount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'billing_same_as_shipping' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
