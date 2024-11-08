<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_amount',
        'payment_method',
        'ship_method',
        'ship_address_id',
        'status',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(Order_detail::class);
    }
}
