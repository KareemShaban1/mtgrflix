<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = ['uses_count', 'unique_users_count', 'total_discount'];

    protected function usesCount(): Attribute
    {
        return Attribute::get(fn () => $this->usageCount());
    }

    protected function uniqueUsersCount(): Attribute
    {
        return Attribute::get(fn () => $this->uniqueClientsCount());
    }

    protected function totalDiscount(): Attribute
    {
        return Attribute::get(fn () => round($this->totalDiscountAmount(), 2));
    }

    // public function orders()
    // {
    //     return $this->belongsToMany(Order::class);
    // }


    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function testimonial(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Testimonial::class);
    }

    public function usageCount(): int
    {
        return $this->orders()->count();
    }

    public function totalDiscountAmount(): float
    {
        return $this->orders()->sum('grand_total');
    }
    
    
    

    public function uniqueClientsCount(): int
    {
        return $this->orders()->distinct('user_id')->count('user_id');
    }
}
