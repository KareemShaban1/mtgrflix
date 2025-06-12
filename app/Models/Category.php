<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'identifier',
        'is_active',
        'parent_id',
        'link'
    ];
    protected $translatable = [
        'name',
    ];
    public function products()
    {
        return $this->hasMany(Product::class)->where('is_active', 1);
    }


    public function getImageUrlAttribute()
    {
        $path = 'storage/' . $this->image;

        if ($this->image && file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('frontend/assets/image/logo.avif');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('is_active', 1);
    }



     protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->identifier = self::generateIdentifier();
        });
    }

    private static function generateIdentifier()
    {
        do {
            $identifier = 'c' . mt_rand(10000000, 99999999);
        } while (self::where('identifier', $identifier)->exists());

        return $identifier;
    }

}
