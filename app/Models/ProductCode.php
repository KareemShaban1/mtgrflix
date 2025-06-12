<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id', 'code', 'is_active','used_at'
    ];

    /**
     * Relationship to Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship to User model (nullable).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
