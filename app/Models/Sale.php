<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'sale_date',
        'sale_number',
        'customer_id',
        'currency_id',
        'exchange_rate',
        'discount',
        'paid_amount',
        'sub_total',
        'total_amount',
        'amount_in_base_currency',
        'status',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'discount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_in_base_currency' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            $sale->sale_number = Sale::generateSaleNumber();

            if ($sale->exchange_rate === null && $sale->currency) {
                $sale->exchange_rate = $sale->currency->exchange_rate;
            }

            // Set amount_in_base_currency
            if ($sale->total_amount !== null && $sale->currency) {
                $sale->amount_in_base_currency = $sale->currency->convertFromCurrency($sale->total_amount, $sale->currency->code);
            }

        });

        static::updating(function ($sale) {
            // Update amount_in_base_currency when total_amount or currency changes
            if ($sale->isDirty(['total_amount', 'currency_id', 'exchange_rate']) && $sale->currency) {
                $sale->amount_in_base_currency = $sale->currency->convertFromCurrency($sale->total_amount, $sale->currency->code);
            }
        });
    }

    public function returns(): HasMany
    {
        return $this->hasMany(SaleReturn::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getCustomerNameAttribute()
    {
        return $this->customer ? $this->customer->name : __('Walk-in Customer');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function getAmountInBaseCurrencyAttribute()
    {
        if ($this->currency) {
            return $this->currency->convertFromCurrency($this->total_amount, $this->currency->code);
        }

        return $this->total_amount;
    }

    public function getFormattedTotalAttribute()
    {
        $currency = $this->currency;
        if ($currency) {
            $decimalPlaces = $currency->decimal_places ?? 2;

            return number_format($this->total_amount, $decimalPlaces).' '.$currency->symbol;
        }

        return number_format($this->total_amount, 2);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function isPaid()
    {
        return $this->paid_amount >= $this->total_amount;
    }

    public function isPartiallyPaid()
    {
        return $this->paid_amount > 0 && $this->paid_amount < $this->total_amount;
    }

    public function isUnpaid()
    {
        return $this->paid_amount <= 0;
    }

    public static function generateSaleNumber(): string
    {
        $lastSale = Sale::latest()->first();

        return
            $lastSale
            ? 'INV-'.str_pad($lastSale->id + 1, 4, '0', STR_PAD_LEFT)
            : 'INV-0001';
    }
}
