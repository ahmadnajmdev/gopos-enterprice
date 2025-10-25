<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            // Set exchange_rate from currency if not set
            if (empty($expense->exchange_rate) && $expense->currency) {
                $expense->exchange_rate = $expense->currency->exchange_rate;
            }

            // Set amount_in_base_currency
            if (! empty($expense->amount) && $expense->currency) {
                $expense->amount_in_base_currency = $expense->currency->convertFromCurrency($expense->amount, $expense->currency->code);
            }
        });
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function getAmountInBaseCurrencyAttribute()
    {
        return $this->currency?->convertFromCurrency($this->amount, $this->currency->code);
    }

    public function type()
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }
}
