<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'rank',
        'status',
        'created_by'
    ];
}