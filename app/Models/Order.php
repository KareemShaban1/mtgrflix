<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Order extends Model
{
    use HasFactory;


    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusId()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($order) {
        //     $lastOrder = self::latest('id')->first();
        //     $lastNumber = $lastOrder?->number ?? 180011123;

        //     $order->number = (string)((int)$lastNumber + 1);
        // });

        // static::created(function ($order) {
        //     if (is_null($order->viewed_at)) {
        //         $order->update(['viewed_at' => null]);
        //     }
        // });
    }


protected $appends = ['vat_amount'];

    public function options(): HasManyThrough
    {
        return $this->hasManyThrough(
            OrderItemOption::class,
            OrderItem::class,
            'order_id',
            'order_item_id',
            'id',
            'id'
        );
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function getExchangeRateAttribute($value)
    {
        return $value ?? 1;
    }


    public function getProductItemsAttribute($value)
    {
        $value = trim($value, "'\"");

        // Clean up SKU and Qty
        $cleaned = str_replace('(SKU: )', '', $value);
        $cleaned = preg_replace_callback('/\(Qty:\s*(\d+)\)/', function ($matches) {
            return ' × ' . $matches[1];
        }, $cleaned);

        return $cleaned;
    }


    public function getVatAmountAttribute()
    {
        $fee = 0;

        if ($this->payment && $this->payment->data) {
            $paymentData = $this->payment->data;

            if (
                isset($paymentData['InvoiceTransactions']) &&
                is_array($paymentData['InvoiceTransactions']) &&
                !empty($paymentData['InvoiceTransactions'])
            ) {
                $firstTransaction = $paymentData['InvoiceTransactions'][0];
                $fee = (float) ($firstTransaction['TotalServiceCharge'] ?? 0);
            }
        }

        return $fee;
    }
}
