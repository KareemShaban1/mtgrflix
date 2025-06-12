<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

   protected $guarded = [];



    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function scopeFilter($query, array $filters)
    // {
    //     $query->when($filters['search'] ?? false, function ($query, $search) {
    //         $query->where('review', 'like', '%' . $search . '%');
    //     });
    // }
}
