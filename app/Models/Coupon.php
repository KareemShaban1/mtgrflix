<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;


    protected $fillable = [
        'code',
        'type',
        'value',
        'valid_from',
        'valid_until',
        'max_uses',
        'max_uses_per_user',
        'min_purchase_amount',
    ];
    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    // public function orders()
    // {
    //     return $this->belongsToMany(Order::class);
    // }


    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function testimonial(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Testimonial::class);
    }
}
