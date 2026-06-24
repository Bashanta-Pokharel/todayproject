<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\Product;
use App\Services\CartSummary;
use App\Services\EsewaGateway;
use App\Services\KhaltiGateway;
use App\Services\PaypalGateway;
use App\Services\StripeGateway;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class CheckoutController extends Controller
{
    public function show(CartSummary $cartSummary): View|RedirectResponse
    {
        $summary = $cartSummary->current(session('coupon_code'));

        if ($summary['items']->isEmpty()) {
            return redirect()->route('frontend.cart')->withErrors(['cart' => 'Your cart is empty.']);
        }

        if ($summary['errors'] !== []) {
            return redirect()->route('frontend.cart')->withErrors($summary['errors']);
        }

        $data = $this->baseData();
        $data['summary'] = $summary;
        $data['paymentMethods'] = $this->paymentMethods();

        return view('frontend.checkout', compact('data'));
    }

    public function store(
        Request $request,
        CartSummary $cartSummary,
        EsewaGateway $esewaGateway,
        KhaltiGateway $khaltiGateway,
        PaypalGateway $paypalGateway,
        StripeGateway $stripeGateway
    ): View|RedirectResponse {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_state' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:20'],
            'shipping_country' => ['required', 'string', 'max:80'],
            'payment_method' => ['required', Rule::in(array_keys($this->paymentMethods()))],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $summary = $cartSummary->current(session('coupon_code'));

        if ($summary['items']->isEmpty()) {
            return redirect()->route('frontend.cart')->withErrors(['cart' => 'Your cart is empty.']);
        }

        if ($summary['errors'] !== []) {
            return redirect()->route('frontend.cart')->withErrors($summary['errors']);
        }

        try {
            $order = $this->createOrder($validated, $summary);

            return match ($validated['payment_method']) {
                'cash_on_delivery' => $this->confirmCashOnDelivery($order),
                'esewa' => $this->startEsewa($order, $esewaGateway),
                'khalti' => $this->startKhalti($order, $khaltiGateway),
                'paypal' => $this->startPaypal($order, $paypalGateway),
                'stripe' => $this->startStripe($order, $stripeGateway),
            };
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('frontend.checkout')
                ->withErrors(['checkout' => 'We could not place your order. Please review your cart and try again.']);
        }
    }

    public function esewaSuccess(Request $request, EsewaGateway $esewaGateway): RedirectResponse
    {
        $payload = $esewaGateway->decodeCallback($request);
        $orderNumber = $payload['transaction_uuid'] ?? null;
        $status = strtoupper((string) ($payload['status'] ?? ''));

        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if (! $esewaGateway->hasValidSignature($payload) || ! $esewaGateway->matchesOrder($order, $payload) || $status !== 'COMPLETE') {
            $this->cancelOrder($order, 'failed', $payload);

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'esewa']);
        }

        $transaction = $order->transactions()->updateOrCreate(
            ['provider' => 'esewa', 'reference' => $order->order_number],
            [
                'transaction_code' => $payload['transaction_code'] ?? null,
                'amount' => $order->grand_total,
                'currency' => 'NPR',
                'status' => 'paid',
                'payload' => $payload,
                'paid_at' => now(),
            ]
        );

        $order->markPaid($transaction->transaction_code);
        \Cart::clear();
        session()->forget('coupon_code');

        return redirect()->route('payments.success', $order->order_number);
    }

    public function khaltiSuccess(Request $request, Order $order, KhaltiGateway $khaltiGateway): RedirectResponse
    {
        $pidx = (string) $request->query('pidx');

        if (! $khaltiGateway->isConfigured() || $pidx === '') {
            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'khalti']);
        }

        $payload = $khaltiGateway->lookup($pidx)->json();

        if (! $khaltiGateway->isValidPayment($order, $payload)) {
            $this->cancelOrder($order, 'failed', $payload);

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'khalti']);
        }

        $order->transactions()->updateOrCreate(
            ['provider' => 'khalti', 'reference' => $pidx],
            [
                'amount' => $order->grand_total,
                'currency' => 'NPR',
                'status' => 'paid',
                'transaction_code' => $payload['transaction_id'] ?? null,
                'payload' => $payload,
                'paid_at' => now(),
            ]
        );

        $order->markPaid($pidx);
        \Cart::clear();
        session()->forget('coupon_code');

        return redirect()->route('payments.success', $order->order_number);
    }

    public function paypalSuccess(Request $request, Order $order, PaypalGateway $paypalGateway): RedirectResponse
    {
        $paypalOrderId = (string) $request->query('token');

        if (! $paypalGateway->isConfigured() || $paypalOrderId === '') {
            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'paypal']);
        }

        $payload = $paypalGateway->capture($paypalOrderId)->json();

        if (! $paypalGateway->isValidCapture($order, $payload)) {
            $this->cancelOrder($order, 'failed', $payload);

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'paypal']);
        }

        $order->transactions()->updateOrCreate(
            ['provider' => 'paypal', 'reference' => $paypalOrderId],
            [
                'transaction_code' => data_get($payload, 'purchase_units.0.payments.captures.0.id'),
                'amount' => $order->grand_total,
                'currency' => config('payment.paypal.currency', 'USD'),
                'status' => 'paid',
                'payload' => $payload,
                'paid_at' => now(),
            ]
        );

        $order->markPaid($paypalOrderId);
        \Cart::clear();
        session()->forget('coupon_code');

        return redirect()->route('payments.success', $order->order_number);
    }

    public function stripeSuccess(Request $request, Order $order, StripeGateway $stripeGateway): RedirectResponse
    {
        $sessionId = (string) $request->query('session_id');

        if (! $stripeGateway->isConfigured() || $sessionId === '') {
            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'stripe']);
        }

        $payload = $stripeGateway->retrieveSession($sessionId)->json();

        if (! $stripeGateway->isValidSession($order, $payload)) {
            $this->cancelOrder($order, 'failed', $payload);

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'stripe']);
        }

        $order->transactions()->updateOrCreate(
            ['provider' => 'stripe', 'reference' => $sessionId],
            [
                'transaction_code' => $payload['payment_intent'] ?? null,
                'amount' => $order->grand_total,
                'currency' => strtoupper((string) config('payment.stripe.currency', 'USD')),
                'status' => 'paid',
                'payload' => $payload,
                'paid_at' => now(),
            ]
        );

        $order->markPaid($sessionId);
        \Cart::clear();
        session()->forget('coupon_code');

        return redirect()->route('payments.success', $order->order_number);
    }

    public function success(string $orderNumber): View
    {
        $data = $this->baseData();
        $data['order'] = Order::with(['items.product.images', 'transactions'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('frontend.order-success', compact('data'));
    }

    public function failure(Request $request): View
    {
        $data = $this->baseData();
        $data['order'] = Order::with('items')
            ->where('order_number', $request->query('order'))
            ->first();
        $data['gateway'] = $request->query('gateway');

        if ($data['order'] instanceof Order && $data['order']->payment_status === 'pending') {
            $this->cancelOrder($data['order'], 'failed', $request->query());
        }

        return view('frontend.payment-failure', compact('data'));
    }

    /**
     * @param  array<string, mixed>  $validated
     * @param  array<string, mixed>  $summary
     */
    private function createOrder(array $validated, array $summary): Order
    {
        return DB::transaction(function () use ($validated, $summary): Order {
            $coupon = $summary['coupon'];

            $order = Order::create([
                'order_number' => $this->orderNumber(),
                'customer_id' => auth('customer')->id(),
                'coupon_id' => $coupon?->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'] ?? null,
                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                'shipping_country' => $validated['shipping_country'],
                'payment_method' => $validated['payment_method'],
                'subtotal' => $summary['subtotal'],
                'discount_total' => $summary['discount_total'],
                'shipping_total' => $summary['shipping_total'],
                'tax_total' => $summary['tax_total'],
                'grand_total' => $summary['grand_total'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($summary['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product']->id);

                if ($product->quantity < $item['quantity']) {
                    throw new \RuntimeException("{$product->title} is no longer available in the requested quantity.");
                }

                $product->decrement('quantity', $item['quantity']);

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'product_slug' => $product->slug,
                    'category_title' => $product->category?->title,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => (float) $product->discount,
                    'line_total' => $item['line_total'],
                    'attributes' => $item['attributes'],
                ]);
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            PaymentTransaction::create([
                'order_id' => $order->id,
                'provider' => $validated['payment_method'],
                'reference' => $order->order_number,
                'amount' => $order->grand_total,
                'currency' => config('payment.default_currency', 'NPR'),
                'status' => $validated['payment_method'] === 'cash_on_delivery' ? 'pending_collection' : 'pending',
            ]);

            return $order->load('items');
        });
    }

    private function confirmCashOnDelivery(Order $order): RedirectResponse
    {
        $order->update([
            'order_status' => 'confirmed',
            'payment_status' => 'pending',
            'confirmed_at' => now(),
        ]);

        \Cart::clear();
        session()->forget('coupon_code');

        return redirect()->route('payments.success', $order->order_number);
    }

    private function startEsewa(Order $order, EsewaGateway $gateway): View|RedirectResponse
    {
        if (! $gateway->isConfigured()) {
            $this->cancelOrder($order, 'configuration_error');

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'esewa']);
        }

        $data = $this->baseData();
        $data['paymentUrl'] = $gateway->paymentUrl();
        $data['formData'] = $gateway->formData($order);

        return view('frontend.payment-redirect', compact('data'));
    }

    private function startKhalti(Order $order, KhaltiGateway $gateway): RedirectResponse
    {
        if (! $gateway->isConfigured()) {
            $this->cancelOrder($order, 'configuration_error');

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'khalti']);
        }

        $payload = $gateway->initiate($order)->json();
        $paymentUrl = $payload['payment_url'] ?? null;
        $pidx = $payload['pidx'] ?? null;

        if (! $paymentUrl || ! is_string($pidx) || $pidx === '') {
            $this->cancelOrder($order, 'initiation_failed', $payload);

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'khalti']);
        }

        $this->updatePaymentReference($order, 'khalti', $pidx, $payload);

        return redirect()->away($paymentUrl);
    }

    private function startPaypal(Order $order, PaypalGateway $gateway): RedirectResponse
    {
        if (! $gateway->isConfigured()) {
            $this->cancelOrder($order, 'configuration_error');

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'paypal']);
        }

        $payload = $gateway->createOrder($order)->json();
        $approvalUrl = $gateway->approvalUrl($payload);
        $paypalOrderId = $payload['id'] ?? null;

        if (! $approvalUrl || ! is_string($paypalOrderId) || $paypalOrderId === '') {
            $this->cancelOrder($order, 'initiation_failed', $payload);

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'paypal']);
        }

        $this->updatePaymentReference($order, 'paypal', $paypalOrderId, $payload);

        return redirect()->away($approvalUrl);
    }

    private function startStripe(Order $order, StripeGateway $gateway): RedirectResponse
    {
        if (! $gateway->isConfigured()) {
            $this->cancelOrder($order, 'configuration_error');

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'stripe']);
        }

        $payload = $gateway->createSession($order)->json();
        $checkoutUrl = $payload['url'] ?? null;
        $sessionId = $payload['id'] ?? null;

        if (! $checkoutUrl || ! is_string($sessionId) || $sessionId === '') {
            $this->cancelOrder($order, 'initiation_failed', $payload);

            return redirect()->route('payments.failure', ['order' => $order->order_number, 'gateway' => 'stripe']);
        }

        $this->updatePaymentReference($order, 'stripe', $sessionId, $payload);

        return redirect()->away($checkoutUrl);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function cancelOrder(Order $order, string $reason, array $payload = []): void
    {
        if ($order->payment_status === 'paid' || $order->order_status === 'cancelled') {
            return;
        }

        DB::transaction(function () use ($order, $reason, $payload): void {
            $order->loadMissing('items');

            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::whereKey($item->product_id)->increment('quantity', $item->quantity);
                }
            }

            if ($order->coupon_id) {
                DB::table('coupons')->where('id', $order->coupon_id)->where('used_count', '>', 0)->decrement('used_count');
            }

            $order->update([
                'order_status' => 'cancelled',
                'payment_status' => 'failed',
            ]);

            $order->transactions()->latest()->first()?->update([
                'status' => $reason,
                'payload' => $payload,
            ]);
        });
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function updatePaymentReference(Order $order, string $provider, string $reference, array $payload): void
    {
        $order->transactions()
            ->where('provider', $provider)
            ->where('reference', $order->order_number)
            ->latest()
            ->first()
            ?->update([
                'reference' => $reference,
                'payload' => $payload,
            ]);
    }

    private function orderNumber(): string
    {
        do {
            $number = 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * @return array<string, string>
     */
    private function paymentMethods(): array
    {
        $methods = [];

        if ((bool) config('payment.esewa.enabled') && filled(config('payment.esewa.product_code')) && filled(config('payment.esewa.secret_key'))) {
            $methods['esewa'] = 'eSewa';
        }

        $methods['cash_on_delivery'] = 'Cash on Delivery';

        if ((bool) config('payment.paypal.enabled') && filled(config('payment.paypal.client_id')) && filled(config('payment.paypal.client_secret'))) {
            $methods['paypal'] = 'PayPal';
        }

        if ((bool) config('payment.khalti.enabled') && filled(config('payment.khalti.secret_key'))) {
            $methods['khalti'] = 'Khalti';
        }

        if ((bool) config('payment.stripe.enabled') && filled(config('payment.stripe.secret_key'))) {
            $methods['stripe'] = 'Stripe Test Mode';
        }

        return $methods;
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
