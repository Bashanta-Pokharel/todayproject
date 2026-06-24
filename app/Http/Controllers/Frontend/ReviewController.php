<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:120'],
            'body' => ['nullable', 'string', 'max:1000'],
        ]);

        ProductReview::updateOrCreate(
            [
                'product_id' => $product->id,
                'customer_id' => auth('customer')->id(),
            ],
            [
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'body' => $validated['body'] ?? null,
                'is_approved' => false,
            ]
        );

        return back()->with('success', 'Thanks for your review. It will appear after moderation.');
    }
}
