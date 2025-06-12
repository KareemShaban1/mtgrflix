<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'type', // select, text, etc.
        'field_name', // for select
        'key', // for select
        'value',
        'product_field_id',
    ];
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
