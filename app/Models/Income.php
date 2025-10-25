<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($income) {

            // Set exchange_rate from currency if not set
            if (empty($income->exchange_rate) && $income->currency) {
                $income->exchange_rate = $income->currency->exchange_rate;
            }

            // Set amount_in_base_currency
            if (! empty($income->amount) && $income->currency) {
                $income->amount_in_base_currency = $income->currency->convertFromCurrency($income->amount, $income->currency->code);
            }
        });

        static::updating(function ($income) {
            // Update amount_in_base_currency when amount or currency changes
            if ($income->isDirty(['amount', 'currency_id', 'exchange_rate']) && $income->currency) {
                $income->amount_in_base_currency = $income->currency->convertFromCurrency($income->amount, $income->currency->code);
            }
        });
    }

    public function type()
    {
        return $this->belongsTo(IncomeType::class, 'income_type_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
