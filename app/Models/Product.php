<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'price',
        'discount',
        'description',
        'quantity',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount' => 'decimal:2',
            'quantity' => 'integer',
            'status' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(
            Attribute::class,
            'attribute_product',
            'product_id',
            'attribute_id'
        )
            ->withPivot([
                'values',
                'status',
                'created_by',
                'updated_by',
            ])
            ->withTimestamps();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('is_approved', true);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function getSalePriceAttribute(): float
    {
        return max(0, (float) $this->price - (float) $this->discount);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->quantity > 0;
    }
}
