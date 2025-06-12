<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'unique_key',
        'quantity',
        'price',
        'options',
        'type',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


   

    

    public function getFinalPriceAttribute()
    {
        $rate = session('rate', 1); 

       return $this->price * $rate;
    }

}
