<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'type',
        'reference_id',
    ];

    /**
     * Get the product associated with the stock log.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
