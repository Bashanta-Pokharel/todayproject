<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartSummary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartSummary $cartSummary): View
    {
        $data = $this->baseData();
        $data['summary'] = $cartSummary->current(session('coupon_code'));

        return view('frontend.cart', compact('data'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'attribute' => ['nullable', 'array'],
        ]);

        $product = Product::with('attributes')
            ->active()
            ->whereKey($validated['product_id'])
            ->firstOrFail();

        $currentQuantity = (int) optional(\Cart::get($product->id))->quantity;
        $requestedQuantity = (int) $validated['quantity'];

        if ($currentQuantity + $requestedQuantity > $product->quantity) {
            return back()->withErrors([
                'quantity' => "{$product->title} only has {$product->quantity} item(s) in stock.",
            ]);
        }

        \Cart::add(
            $product->id,
            $product->title,
            $product->sale_price,
            $requestedQuantity,
            $this->validatedAttributes($product, $validated['attribute'] ?? [])
        );

        return back()->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$product->quantity],
        ]);

        if (! \Cart::has($product->id)) {
            return back()->withErrors(['cart' => 'This product is not in your cart.']);
        }

        \Cart::update($product->id, [
            'quantity' => [
                'relative' => false,
                'value' => (int) $validated['quantity'],
            ],
        ]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Product $product): RedirectResponse
    {
        \Cart::remove($product->id);

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear(): RedirectResponse
    {
        \Cart::clear();
        session()->forget('coupon_code');

        return redirect()->route('frontend.cart')->with('success', 'Cart cleared.');
    }

    public function applyCoupon(Request $request, CartSummary $cartSummary): RedirectResponse
    {
        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:50'],
        ]);

        $couponCode = strtoupper(trim($validated['coupon_code']));
        $coupon = Coupon::where('code', $couponCode)->first();
        $summary = $cartSummary->current();

        if (! $coupon || ! $coupon->isUsableFor($summary['subtotal'])) {
            return back()->withErrors(['coupon_code' => 'This coupon is not valid for your cart.']);
        }

        session(['coupon_code' => $couponCode]);

        return back()->with('success', 'Coupon applied.');
    }

    public function removeCoupon(): RedirectResponse
    {
        session()->forget('coupon_code');

        return back()->with('success', 'Coupon removed.');
    }

    /**
     * @param  array<string, mixed>  $selectedAttributes
     * @return array<string, string>
     */
    private function validatedAttributes(Product $product, array $selectedAttributes): array
    {
        $attributes = [];

        foreach ($product->attributes as $attribute) {
            $selected = trim((string) ($selectedAttributes[$attribute->id] ?? ''));

            if ($selected === '') {
                continue;
            }

            $allowed = collect(explode(',', (string) $attribute->pivot->values))
                ->map(fn (string $value): string => trim($value))
                ->filter()
                ->all();

            if (in_array($selected, $allowed, true)) {
                $attributes[$attribute->title] = $selected;
            }
        }

        return $attributes;
    }

    /**
     * @return array<string, mixed>
     */
    private function baseData(): array
    {
        return [
            'categories' => Category::withCount('products')
                ->active()
                ->orderBy('rank')
                ->get(),
        ];
    }
}
