<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeType extends Model
{
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
