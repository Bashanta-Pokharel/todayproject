<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//#[Fillable(['title','slug','rank','status','created_by','updated_by'])]
class ProductImage extends Model
{
    use SoftDeletes;
    protected $fillable = ['product_id','image_name','image_title','status','created_by','updated_by'];
}