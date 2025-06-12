<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory, HasTranslations;
    protected $table = 'currencies';
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
        'order',
    ];  


    protected $translatable = [
        'name',
    ];
}
