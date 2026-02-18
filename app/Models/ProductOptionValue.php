<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionValue extends Model
{
    protected $fillable = [
        'product_option_id',
        'option_value_id',
        'price',
        'price_prefix',
        'quantity',
        'quantity_prefix',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_prefix' => 'boolean',
        'quantity_prefix' => 'boolean',
    ];

    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class);
    }

    public function optionValue(): BelongsTo
    {
        return $this->belongsTo(OptionValue::class);
    }
}
