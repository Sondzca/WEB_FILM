<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'image',
        'startday',
        'enday',
        'price',
        'description',
        'quantity',
        'sell_quantity',
        'is_active',
        'nguoitochuc',
        'address',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
   
    
}
