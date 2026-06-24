<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class StripeGateway
{
    public function isConfigured(): bool
    {
        return (bool) config('payment.stripe.enabled') && filled(config('payment.stripe.secret_key'));
    }

    public function createSession(Order $order): Response
    {
        return Http::timeout(20)
            ->asForm()
            ->withToken((string) config('payment.stripe.secret_key'))
            ->post($this->url('/checkout/sessions'), [
                'mode' => 'payment',
                'client_reference_id' => $order->order_number,
                'customer_email' => $order->customer_email,
                'success_url' => route('payments.stripe.success', ['order' => $order->order_number]).'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payments.failure', ['order' => $order->order_number, 'gateway' => 'stripe']),
                'line_items' => [
                    [
                        'quantity' => 1,
                        'price_data' => [
                            'currency' => config('payment.stripe.currency'),
                            'unit_amount' => (int) round((float) $order->grand_total * 100),
                            'product_data' => [
                                'name' => "Order {$order->order_number}",
                            ],
                        ],
                    ],
                ],
                'metadata' => [
                    'order_number' => $order->order_number,
                ],
            ]);
    }

    public function retrieveSession(string $sessionId): Response
    {
        return Http::timeout(20)
            ->withToken((string) config('payment.stripe.secret_key'))
            ->get($this->url("/checkout/sessions/{$sessionId}"));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function isValidSession(Order $order, array $payload): bool
    {
        return ($payload['payment_status'] ?? null) === 'paid'
            && ($payload['client_reference_id'] ?? null) === $order->order_number
            && strtoupper((string) ($payload['currency'] ?? '')) === strtoupper((string) config('payment.stripe.currency'))
            && (int) ($payload['amount_total'] ?? -1) === (int) round((float) $order->grand_total * 100);
    }

    private function url(string $path): string
    {
        return rtrim((string) config('payment.stripe.base_url'), '/').$path;
    }
}
