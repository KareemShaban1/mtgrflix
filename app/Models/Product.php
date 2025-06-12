<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'products';
    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
    ];

    public $translatable = [
        'name','sub_title'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function productFields()
    {
        return $this->hasMany(ProductField::class);
    }

    /**
     * Relationship to ProductCode model.
     * A product can have many product codes.
     */
    public function productCodes()
    {
        return $this->hasMany(ProductCode::class);
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
            $identifier = 'p' . mt_rand(10000000, 99999999);
        } while (self::where('identifier', $identifier)->exists());

        return $identifier;
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function codes()
    {

        return $this->hasMany(ProductCode::class);
    }

    public function getRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1) ?? 0;
    }

    public function getTimesPurchasedAttribute()
    {
        return $this->orderItems()->count();
    }


    public function getEffectivePrice(): float
    {
        $rate = session('rate', 1);

        if ($this->promotional_price !== null && $this->promotional_price < $this->price) {
            return $this->promotional_price * $rate;
        }

        return $this->price * $rate;
    }

    public function getEffectiveBasePrice(): float
    {

        if ($this->promotional_price !== null && $this->promotional_price < $this->price) {
            return $this->promotional_price ;
        }

        return $this->price;
    }
    public function getImagesUrlAttribute()
    {
        $path = 'storage/' . $this->image;

        if ($this->images && file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('frontend/assets/image/logo.avif');
    }
    
}
