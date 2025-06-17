<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'ref_id',
        'visits_count',
        'purchases_count',
        'total_sales',
    ];
}

