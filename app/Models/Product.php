<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'updated_by'
    ];

    /*
    |-----------------------------------
    | CATEGORY RELATION
    |-----------------------------------
    */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /*
    |-----------------------------------
    | PRODUCT IMAGES
    |-----------------------------------
    */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /*
    |-----------------------------------
    | ATTRIBUTES (FIXED + EXPLICIT TABLE)
    |-----------------------------------
    */
    public function attributes()
    {
        return $this->belongsToMany(
                Attribute::class,
                'attribute_product',   // ✅ explicitly define pivot table
                'product_id',          // ✅ foreign key on this model
                'attribute_id'         // ✅ foreign key on related model
            )
            ->withPivot([
                'values',
                'status',
                'created_by',
                'updated_by'
            ])
            ->withTimestamps();
    }

    /*
    |-----------------------------------
    | OPTIONAL: SCOPE (ACTIVE PRODUCTS)
    |-----------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}