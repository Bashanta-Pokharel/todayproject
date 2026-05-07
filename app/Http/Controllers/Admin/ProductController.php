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
use Illuminate\Support\Facades\File;


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
    // Add created_by
    $request->merge([
        'created_by' => Auth::id()
    ]);

    // Create product
    $product = Product::create($request->only([
        'category_id',
        'title',
        'slug',
        'quantity',
        'price',
        'discount',
        'description',
        'status',
        'created_by'
    ]));

    /*
    |-----------------------------------
    | IMAGE UPLOAD
    |-----------------------------------
    */
    if ($request->hasFile('image_name')) {

        foreach ($request->file('image_name') as $index => $file) {

            if ($file && $file->isValid()) {

                $imageName = time().'_'.$index.'.'.$file->getClientOriginalExtension();

                $file->move(public_path('uploads/products'), $imageName);

                ProductImage::create([
                    'product_id'  => $product->id,
                    'image_name'  => $imageName,
                    'image_title' => $request->image_title[$index] ?? null,
                    'status'      => $request->image_status[$index] ?? 0,
                    'created_by'  => Auth::id(),
                ]);
            }
        }
    }

    /*
    |-----------------------------------
    | ATTRIBUTES (FIXED)
    |-----------------------------------
    */
    if ($request->has('attribute_id')) {

        foreach ($request->attribute_id as $index => $attribute_id) {

            // 🚨 skip empty rows
            if (empty($attribute_id)) {
                continue;
            }

            $product->attributes()->attach($attribute_id, [
                'values'     => $request->values[$index] ?? null,
                'status'     => $request->attr_status[$index] ?? 0,
                'created_by' => Auth::id(),
            ]);
        }
    }

    return redirect()
        ->route('admin.product.index')
        ->with('success', 'Product Created Successfully');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Product::with(['category','images','attributes'])->find($id);

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

    public function deleteImage($id)
    {
        $image = ProductImage::find($id);

        if (!$image) {
            return redirect()->back()->with('error', 'Image not found');
        }

        // Delete file from folder
        $path = public_path('uploads/products/' . $image->image_name);
        if (File::exists($path)) {
            File::delete($path);
        }

        // Delete DB record
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully');
    }

    public function deleteAttribute($productId, $attributeId)
    {
        $product = Product::findOrFail($productId);

        // detach removes pivot row
        $product->attributes()->detach($attributeId);

        return redirect()->back()->with('success', 'Attribute removed');
    }

    public function addAttribute(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'attribute_id' => 'required',
            'value' => 'required'
        ]);

        $product->attributes()->attach($request->attribute_id, [
            'values' => $request->value,
            'status' => $request->status ?? 1,
            'created_by' => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Attribute added');
    }
    public function addImage(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'image_name' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'image_title' => 'nullable|string',
        'status' => 'required'
    ]);

    if ($request->hasFile('image_name')) {

        $file = $request->file('image_name');

        $imageName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

        $file->move(public_path('uploads/products'), $imageName);

        ProductImage::create([
            'product_id'  => $product->id,
            'image_name'  => $imageName,
            'image_title' => $request->image_title,
            'status'      => $request->status,
            'created_by'  => Auth::id(),
        ]);
    }

    return redirect()->back()->with('success', 'Image added successfully');
}
}
