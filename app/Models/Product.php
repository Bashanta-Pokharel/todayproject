<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//#[Fillable(['title','slug','rank','status','created_by','updated_by'])]
class Product extends Model
{
    use SoftDeletes;
    protected $fillable = ['category_id','title','slug','price','discount','description','quantity','status','created_by','updated_by'];

    public function attributes(){
        return $this->belongsToMany(Attribute::class)->withPivot('values','status','created_by','updated_by')->withTimestamps();
    }
}