<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public   function index()
    {
        $data['categories']=Category::where('status',1)
            ->orderby('rank')->get();
        return view('frontend.home',compact('data'));
    }

    public   function listing($slug){
        $data['categories']=Category::where('status',1)
        ->orderby('rank')->get();
        $data['category']=Category::where('slug',$slug)->first();
        $data['products'] = $data['category']->products()->where('status',1)->paginate(9);
        return view('frontend.listing',compact('data'));
    }

    public   function details($slug){
        $data['categories']=Category::where('status',1)
            ->orderby('rank')->get();
        $data['product']=Product::where('slug',$slug)->first();
        return view('frontend.details',compact('data'));
    }
}