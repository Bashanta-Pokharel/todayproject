<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Collection;

class CartSummary
{
    /**
     * @return array{
     *     items: Collection<int, array<string, mixed>>,
     *     subtotal: float,
     *     discount_total: float,
     *     shipping_total: float,
     *     tax_total: float,
     *     grand_total: float,
     *     coupon: Coupon|null,
     *     errors: array<int, string>
     * }
     */
    public function current(?string $couponCode = null): array
    {
        $errors = [];
        $items = collect(\Cart::getContent())->map(function ($cartItem) use (&$errors): ?array {
            $product = Product::with(['category', 'images'])
                ->active()
                ->find($cartItem->id);

            if (! $product) {
                $errors[] = 'A product in your cart is no longer available.';

                return null;
            }

            $quantity = (int) $cartItem->quantity;

            if ($quantity < 1) {
                $errors[] = "{$product->title} has an invalid quantity.";
            }

            if ($quantity > $product->quantity) {
                $errors[] = "{$product->title} only has {$product->quantity} item(s) in stock.";
            }

            $unitPrice = $product->sale_price;

            $attributes = is_array($cartItem->attributes)
                ? $cartItem->attributes
                : ($cartItem->attributes?->toArray() ?? []);

            return [
                'cart_item' => $cartItem,
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => round($unitPrice * $quantity, 2),
                'attributes' => $attributes,
            ];
        })->filter()->values();

        $subtotal = round((float) $items->sum('line_total'), 2);
        $coupon = $this->coupon($couponCode);
        $discountTotal = $coupon?->discountFor($subtotal) ?? 0.0;
        $shippingTotal = $subtotal >= 5000 || $subtotal <= 0 ? 0.0 : 150.0;
        $taxTotal = 0.0;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount_total' => round($discountTotal, 2),
            'shipping_total' => $shippingTotal,
            'tax_total' => $taxTotal,
            'grand_total' => round(max(0, $subtotal - $discountTotal + $shippingTotal + $taxTotal), 2),
            'coupon' => $coupon?->isUsableFor($subtotal) ? $coupon : null,
            'errors' => $errors,
        ];
    }

    private function coupon(?string $couponCode): ?Coupon
    {
        if (! $couponCode) {
            return null;
        }

        return Coupon::where('code', strtoupper(trim($couponCode)))->first();
    }
}
