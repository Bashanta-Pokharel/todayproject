<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PaypalGateway
{
    public function isConfigured(): bool
    {
        return (bool) config('payment.paypal.enabled')
            && filled(config('payment.paypal.client_id'))
            && filled(config('payment.paypal.client_secret'));
    }

    public function createOrder(Order $order): Response
    {
        return Http::timeout(20)
            ->withToken($this->accessToken())
            ->acceptJson()
            ->post($this->url('/v2/checkout/orders'), [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $order->order_number,
                        'amount' => [
                            'currency_code' => config('payment.paypal.currency'),
                            'value' => number_format((float) $order->grand_total, 2, '.', ''),
                        ],
                    ],
                ],
                'application_context' => [
                    'return_url' => route('payments.paypal.success', ['order' => $order->order_number]),
                    'cancel_url' => route('payments.failure', ['order' => $order->order_number, 'gateway' => 'paypal']),
                    'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                    'user_action' => 'PAY_NOW',
                ],
            ]);
    }

    public function capture(string $paypalOrderId): Response
    {
        return Http::timeout(20)
            ->withToken($this->accessToken())
            ->acceptJson()
            ->post($this->url("/v2/checkout/orders/{$paypalOrderId}/capture"));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function approvalUrl(array $payload): ?string
    {
        foreach ($payload['links'] ?? [] as $link) {
            if (($link['rel'] ?? null) === 'approve' || ($link['rel'] ?? null) === 'payer-action') {
                return $link['href'] ?? null;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function isValidCapture(Order $order, array $payload): bool
    {
        $capture = data_get($payload, 'purchase_units.0.payments.captures.0');

        return ($payload['status'] ?? null) === 'COMPLETED'
            && data_get($payload, 'purchase_units.0.reference_id') === $order->order_number
            && ($capture['status'] ?? null) === 'COMPLETED'
            && strtoupper((string) data_get($capture, 'amount.currency_code')) === strtoupper((string) config('payment.paypal.currency'))
            && number_format((float) data_get($capture, 'amount.value', -1), 2, '.', '') === number_format((float) $order->grand_total, 2, '.', '');
    }

    private function accessToken(): string
    {
        $response = Http::timeout(20)
            ->asForm()
            ->withBasicAuth((string) config('payment.paypal.client_id'), (string) config('payment.paypal.client_secret'))
            ->post($this->url('/v1/oauth2/token'), [
                'grant_type' => 'client_credentials',
            ])
            ->throw()
            ->json();

        return (string) $response['access_token'];
    }

    private function url(string $path): string
    {
        return rtrim((string) config('payment.paypal.base_url'), '/').$path;
    }
}
