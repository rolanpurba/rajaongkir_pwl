<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'recipient_name', 'phone', 'address',
        'province', 'city', 'city_id', 'courier', 'courier_service',
        'shipping_cost', 'total_price', 'total_weight', 'status',
    ];

    public function user()  { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
}
