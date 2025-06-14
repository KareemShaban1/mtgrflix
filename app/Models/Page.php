<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory,HasTranslations;
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
    ];

    public $translatable = [
        'title',
        'content',
    ];
}
