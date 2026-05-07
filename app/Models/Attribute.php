<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'status',
        'created_by',
        'updated_by'
    ];

    /*
    |-----------------------------------
    | PRODUCTS RELATION (FIXED)
    |-----------------------------------
    */
    public function products()
    {
        return $this->belongsToMany(
                Product::class,
                'attribute_product',   // ✅ pivot table
                'attribute_id',        // ✅ this model key
                'product_id'           // ✅ related model key
            )
            ->withPivot([
                'values',
                'status',
                'created_by',
                'updated_by'
            ])
            ->withTimestamps();
    }
}