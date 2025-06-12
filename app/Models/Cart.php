<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_token',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate the total price of all items in the cart
     */
    public function getTotal()
    {
        return $this->items->sum(function ($item) {

            return $item->price * $item->quantity;
        });
    }

    /**
     * Alternative method that always calculates based on current product prices
     */
    public function calculateFreshTotal()
    {
        return $this->items->sum(function ($item) {
            return $item->product->getEffectivePrice() * $item->quantity;
        });
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class)->where(function ($query) {
            $now = now();

            $query->where('is_active', true)
                ->where('valid_from', '<=', $now)
                ->where('valid_until', '>=', $now);
        });
    }
}
