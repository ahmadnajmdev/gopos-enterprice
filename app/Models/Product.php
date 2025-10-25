<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Storage;

class Product extends Model
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function getImageUrlAttribute($value)
    {
        return $value ? Storage::url($value) : 'https://placehold.co/400';
    }

    public function getStockAttribute(): int
    {
        // Calculate stock from movements only
        return (int) $this->movements()->sum('quantity');
    }

    /**
     * Scope a query to only include products that are low in stock.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock(Builder $query)
    {
        return $query
            ->select('products.*')
            ->selectRaw('(SELECT COALESCE(SUM(quantity), 0) FROM inventory_movements WHERE inventory_movements.product_id = products.id) as total_quantity')
            ->whereRaw('(SELECT COALESCE(SUM(quantity), 0) FROM inventory_movements WHERE inventory_movements.product_id = products.id) <= products.low_stock_alert');
    }
}
