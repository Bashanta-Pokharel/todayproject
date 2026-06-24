<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'attribute_product',
            'attribute_id',
            'product_id'
        )
            ->withPivot([
                'values',
                'status',
                'created_by',
                'updated_by',
            ])
            ->withTimestamps();
    }
}
