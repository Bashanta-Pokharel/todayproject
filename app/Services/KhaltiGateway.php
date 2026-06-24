<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class KhaltiGateway
{
    public function isConfigured(): bool
    {
        return (bool) config('payment.khalti.enabled') && filled(config('payment.khalti.secret_key'));
    }

    public function initiate(Order $order): Response
    {
        return Http::timeout(15)
            ->withHeaders($this->headers())
            ->post($this->url('/epayment/initiate/'), [
                'return_url' => route('payments.khalti.success', ['order' => $order->order_number]),
                'website_url' => config('app.url'),
                'amount' => (int) round((float) $order->grand_total * 100),
                'purchase_order_id' => $order->order_number,
                'purchase_order_name' => "Order {$order->order_number}",
                'customer_info' => [
                    'name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                ],
            ]);
    }

    public function lookup(string $pidx): Response
    {
        return Http::timeout(15)
            ->withHeaders($this->headers())
            ->post($this->url('/epayment/lookup/'), [
                'pidx' => $pidx,
            ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function isValidPayment(Order $order, array $payload): bool
    {
        return ($payload['status'] ?? null) === 'Completed'
            && ($payload['purchase_order_id'] ?? null) === $order->order_number
            && (int) ($payload['total_amount'] ?? -1) === (int) round((float) $order->grand_total * 100);
    }

    /**
     * @return array<string, string>
     */
    private function headers(): array
    {
        return [
            'Authorization' => 'Key '.config('payment.khalti.secret_key'),
            'Content-Type' => 'application/json',
        ];
    }

    private function url(string $path): string
    {
        return rtrim((string) config('payment.khalti.base_url'), '/').$path;
    }
}
