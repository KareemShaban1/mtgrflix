<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductField extends Model
{

    use HasFactory, HasTranslations;
    protected $fillable = [
        'slug',
        'name',
        'input_type',
        'required',
        'options',
        'multiple',
        'order',
        'product_id'
    ];

    protected $casts = [
        'options' => 'array', // Automatically casts the 'options' attribute to JSON
    ];
    protected $translatable = ['name'];
    /**
     * Relationship to Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function getPriceByKey($key)
    {
        $rate = session('rate', 1);
        $options = json_decode($this->options, true);

        $item = collect($options)->firstWhere('key', $key);

        if (!$item) {
            return null;
        }

        return $item['value'] * $rate;
    }

    // public function getOptionsAttribute($value)
    // {
    //     $rate = session('rate', 1);
    //     $options = json_decode($value, true);

    //     // Update each value by multiplying with rate
    //     return collect($options)->map(function ($option) use ($rate) {
    //         return [
    //             'key' => $option['key'],
    //             'value' => $option['value'] * $rate,
    //         ];
    //     })->toArray();
    // }

    public function getConvertedOptionsAttribute()
    {
        $rate = session('rate', 1);
        $options = $this->attributes['options'] ?? '[]';

        $decoded = json_decode($options, true);

        return collect($decoded)->map(function ($option) use ($rate) {
            return [
                'key'   => $option['key'],
                'value' => $option['value'],
                'converted_price' => $option['value'] * $rate,
            ];
        })->toArray();
    }
}
