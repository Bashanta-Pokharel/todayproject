<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $data = [
            'categories' => Category::withCount('products')->active()->orderBy('rank')->get(),
            'items' => Wishlist::with(['product.category', 'product.images'])
                ->where('customer_id', auth('customer')->id())
                ->latest()
                ->get(),
        ];

        return view('customer.wishlist', compact('data'));
    }

    public function toggle(Product $product): RedirectResponse
    {
        $customerId = auth('customer')->id();
        $wishlist = Wishlist::where('customer_id', $customerId)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return back()->with('success', 'Product removed from wishlist.');
        }

        Wishlist::create([
            'customer_id' => $customerId,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Product added to wishlist.');
    }
}
