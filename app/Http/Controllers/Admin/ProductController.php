<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['records'] = Product::with(['category', 'images'])
            ->latest()
            ->paginate(15);

        return view('admin.product.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['attributes'] = Attribute::orderBy('title')->get();
        $data['categories'] = Category::orderBy('title')->get();

        return view('admin.product.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = Auth::id();
        $validated['discount'] = $validated['discount'] ?? 0;

        $product = Product::create(collect($validated)->only([
            'category_id',
            'title',
            'slug',
            'quantity',
            'price',
            'discount',
            'description',
            'status',
            'created_by',
        ])->all());

        $this->storeImages($request, $product);
        $this->syncAttributes($request, $product, false);

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Product::with(['category', 'images', 'attributes'])->find($id);

        if (! $record) {
            return redirect()->route('admin.product.index')->with('error', 'Product not found.');
        }

        return view('admin.product.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = Product::with(['images', 'attributes'])->find($id);

        if (! $record) {
            return redirect()->route('admin.product.index')->with('error', 'Product not found.');
        }

        $data['attributes'] = Attribute::orderBy('title')->get();
        $data['categories'] = Category::orderBy('title')->get();

        return view('admin.product.edit', compact('record', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCreateRequest $request, string $id)
    {
        $record = Product::find($id);

        if (! $record) {
            return redirect()->route('admin.product.index')
                ->with('error', 'Product not found.');
        }

        $validated = $request->validated();
        $validated['updated_by'] = Auth::id();
        $validated['discount'] = $validated['discount'] ?? 0;

        $record->update(collect($validated)->only([
            'category_id',
            'title',
            'slug',
            'quantity',
            'price',
            'discount',
            'description',
            'status',
            'updated_by',
        ])->all());

        $this->syncAttributes($request, $record, true);

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Product::find($id);

        if (! $record) {
            return redirect()->route('admin.product.index')->with('error', 'Product not found.');
        }

        $record->delete();

        return redirect()->route('admin.product.index')->with('success', 'Product deleted successfully.');
    }

    public function trashed()
    {
        $data['records'] = Product::onlyTrashed()->latest()->get();

        return view('admin.product.trashed', compact('data'));
    }

    public function restore($id)
    {
        $record = Product::onlyTrashed()->findOrFail($id);
        $record->restore();

        return redirect()->route('admin.product.index')->with('success', 'Product restored successfully.');
    }

    public function forceDelete($id)
    {
        $record = Product::onlyTrashed()->findOrFail($id);
        $record->forceDelete();

        return redirect()->route('admin.product.trashed')->with('success', 'Product permanently deleted successfully.');
    }

    public function deleteImage($id)
    {
        $image = ProductImage::find($id);

        if (! $image) {
            return redirect()->back()->with('error', 'Image not found');
        }

        $path = public_path('uploads/products/'.$image->image_name);
        if (File::exists($path)) {
            File::delete($path);
        }

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
            'attribute_id' => ['required', 'exists:attributes,id'],
            'value' => ['required', 'string', 'max:1000'],
            'status' => ['nullable', 'boolean'],
        ]);

        $product->attributes()->syncWithoutDetaching([
            $request->attribute_id => [
                'values' => $request->value,
                'status' => $request->status ?? 1,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ],
        ]);

        return redirect()->back()->with('success', 'Attribute added');
    }

    public function addImage(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'image_name' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_title' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ]);

        $file = $request->file('image_name');

        $imageName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

        $file->move(public_path('uploads/products'), $imageName);

        ProductImage::create([
            'product_id' => $product->id,
            'image_name' => $imageName,
            'image_title' => $request->image_title,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Image added successfully');
    }

    private function storeImages(ProductCreateRequest $request, Product $product): void
    {
        if (! $request->hasFile('image_name')) {
            return;
        }

        foreach ($request->file('image_name') as $index => $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $imageName = time().'_'.$index.'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $imageName);

            ProductImage::create([
                'product_id' => $product->id,
                'image_name' => $imageName,
                'image_title' => $request->image_title[$index] ?? null,
                'status' => $request->image_status[$index] ?? 0,
                'created_by' => Auth::id(),
            ]);
        }
    }

    private function syncAttributes(Request $request, Product $product, bool $detachMissing): void
    {
        $syncData = [];

        foreach ($request->input('attribute_id', []) as $index => $attributeId) {
            if (! $attributeId) {
                continue;
            }

            $syncData[$attributeId] = [
                'values' => $request->input("values.{$index}"),
                'status' => $request->input("attr_status.{$index}", 0),
                'created_by' => $product->exists ? $product->created_by : Auth::id(),
                'updated_by' => Auth::id(),
            ];
        }

        if ($detachMissing) {
            $product->attributes()->sync($syncData);

            return;
        }

        $product->attributes()->attach($syncData);
    }
}
