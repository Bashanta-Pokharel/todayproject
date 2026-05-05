<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\CategoryCreateRequest;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['records'] = Product::all();
        return view('admin.product.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['attributes'] = Attribute::all();
        $data['categories'] = Category::all();
        return view('admin.product.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request)
    {
        $request->request->add(['created_by' => Auth::user()->id]);
        $record = Product::create($request->all());
        if($record){
            //upload multiple image using loop
            $imageData = [];
            $imageData['product_id'] = $record->id;
            $imageData['created_by'] = Auth::user()->id;
           foreach($request->file('image_name') as $index => $file){
                   $imageName = time().'.' . $file->getClientOriginalName();
                   $file->move(public_path('uploads/products'), $imageName);
                   $imageData['image_name'] = $imageName;
                   $imageData['image_title'] = $request->input('image_title')[$index];
                   $imageData['status'] = $request->input('image_status')[$index];
                    ProductImage::create($imageData);
           }
           //insert attribute product table
            foreach ($request->input('attribute_id') as $index => $attribute_id) {
                $newData ['values'] = $request->input('values')[$index];
                $newData ['created_by'] = Auth::user()->id;
                $newData ['status'] = $request->input('attr_status')[$index];
                $record->attributes()->attach($attribute_id, $newData);
            }
        }
        return redirect()->route('admin.product.index')->with('success','Product Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Product::find($id);
        if(!$record){
            return redirect()->route('admin.product.index')->with('error','Product Not Found');
        }
        return view('admin.product.show',compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = Product::find($id);
        if(!$record){
            return redirect()->route('admin.product.index')->with('error','Product Not Found');
        }
        return view('admin.product.edit',compact('record'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Product::find($id);
        if($record){
            $record->update($request->all());
            return redirect()->route('admin.product.index')->with('success','Product Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Product::find($id);
        $record->delete();
        return redirect()->route('admin.product.index')->with('success','Product Deleted  Successfully');
    }

    public function trashed()
    {
        $data['records'] = Product::onlyTrashed()->get();
        return view('admin.product.trashed',compact('data'));
    }

    public function restore($id){
        $record = Product::onlyTrashed()->findOrFail($id);
        $record->restore();
        return redirect()->route('admin.product.index')->with('success','Product Restored  Successfully');
    }

    public function forceDelete($id){
        $record = Product::onlyTrashed()->findOrFail($id);
        $record->forceDelete();
        return redirect()->route('admin.product.trashed')->with('success','Product Permanently Deleted  Successfully');
    }
}



