<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        $data['category']=Category::where('slug',$slug)->first();
        dd($data['category']->products->toArray());
        return view('frontend.listing');
    }

    public   function details($slug){
        return view('frontend.details');
    }
}
