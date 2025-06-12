<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'order_id',
    //     'product_id',
    //     'quantity',
    //     'unit_amount',
    //     'total_amount',
    // ];
protected $guarded = [];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function itemOptions()
    {
        return $this->hasMany(OrderItemOption::class);
    }


    public function selectedCode()
    {
        return $this->belongsTo(ProductCode::class, 'product_code_id','id');
    }
}
