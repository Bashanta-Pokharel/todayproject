<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'used_count',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'minimum_amount' => 'decimal:2',
            'maximum_discount' => 'decimal:2',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isUsableFor(float $subtotal): bool
    {
        if (! $this->is_active || $subtotal < (float) $this->minimum_amount) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($this->starts_at !== null && $this->starts_at->isFuture()) {
            return false;
        }

        return $this->ends_at === null || $this->ends_at->isFuture();
    }

    public function discountFor(float $subtotal): float
    {
        if (! $this->isUsableFor($subtotal)) {
            return 0.0;
        }

        $discount = $this->type === 'percent'
            ? $subtotal * ((float) $this->value / 100)
            : (float) $this->value;

        if ($this->maximum_discount !== null) {
            $discount = min($discount, (float) $this->maximum_discount);
        }

        return round(min($discount, $subtotal), 2);
    }
}
