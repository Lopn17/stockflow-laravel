<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  // ADD THIS
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use HasFactory;  // ADD THIS
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'notes',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity'         => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Badge color helper for the UI
    public function typeBadgeColor(): string
    {
        return match($this->type) {
            'stock_in'   => 'green',
            'stock_out'  => 'red',
            'adjustment' => 'yellow',
        };
    }

    public function typeLabel(): string
    {
        return match($this->type) {
            'stock_in'   => 'Stock In',
            'stock_out'  => 'Stock Out',
            'adjustment' => 'Adjustment',
        };
    }
}