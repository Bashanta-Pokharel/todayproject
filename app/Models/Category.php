<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
    #[Fillable(['title', 'slug', 'rank', 'status', 'created_by', 'updated_by'])]
    class Category extends Model
    {
        use SoftDeletes;

        protected $fillable = ['title', 'slug', 'rank', 'status', 'created_by', 'updated_by'];
    }
    

