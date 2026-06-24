<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EsewaGateway
{
    public function isConfigured(): bool
    {
        return (bool) config('payment.esewa.enabled')
            && filled(config('payment.esewa.product_code'))
            && filled(config('payment.esewa.secret_key'));
    }

    /**
     * @return array<string, string>
     */
    public function formData(Order $order): array
    {
        $amount = $this->formatAmount((float) $order->grand_total - (float) $order->shipping_total - (float) $order->tax_total);
        $taxAmount = $this->formatAmount((float) $order->tax_total);
        $deliveryCharge = $this->formatAmount((float) $order->shipping_total);
        $totalAmount = $this->formatAmount((float) $order->grand_total);
        $productCode = (string) config('payment.esewa.product_code');

        return [
            'amount' => $amount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'transaction_uuid' => $order->order_number,
            'product_code' => $productCode,
            'product_service_charge' => '0',
            'product_delivery_charge' => $deliveryCharge,
            'success_url' => route('payments.esewa.success'),
            'failure_url' => route('payments.failure', ['order' => $order->order_number, 'gateway' => 'esewa']),
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
            'signature' => $this->signature([
                'total_amount' => $totalAmount,
                'transaction_uuid' => $order->order_number,
                'product_code' => $productCode,
            ]),
        ];
    }

    public function paymentUrl(): string
    {
        return (string) config('payment.esewa.payment_url');
    }

    /**
     * @return array<string, mixed>
     */
    public function decodeCallback(Request $request): array
    {
        if ($request->filled('data')) {
            $decoded = base64_decode((string) $request->query('data'), true);

            if ($decoded === false) {
                return [];
            }

            $payload = json_decode($decoded, true);

            return is_array($payload) ? $payload : [];
        }

        return $request->query();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function hasValidSignature(array $payload): bool
    {
        if (empty($payload['signed_field_names']) || empty($payload['signature'])) {
            return false;
        }

        $fields = explode(',', (string) $payload['signed_field_names']);
        $data = [];

        foreach ($fields as $field) {
            if (! array_key_exists($field, $payload)) {
                return false;
            }

            $data[$field] = (string) $payload[$field];
        }

        return hash_equals((string) $payload['signature'], $this->signature($data));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function matchesOrder(Order $order, array $payload): bool
    {
        return ($payload['transaction_uuid'] ?? null) === $order->order_number
            && ($payload['product_code'] ?? null) === config('payment.esewa.product_code')
            && $this->formatAmount((float) ($payload['total_amount'] ?? -1)) === $this->formatAmount((float) $order->grand_total);
    }

    public function status(Order $order): ?Response
    {
        return Http::timeout(10)->get((string) config('payment.esewa.status_url'), [
            'product_code' => config('payment.esewa.product_code'),
            'total_amount' => $this->formatAmount((float) $order->grand_total),
            'transaction_uuid' => $order->order_number,
        ]);
    }

    /**
     * @param  array<string, string>  $data
     */
    private function signature(array $data): string
    {
        $message = collect($data)
            ->map(fn (string $value, string $key): string => "{$key}={$value}")
            ->implode(',');

        return base64_encode(hash_hmac('sha256', $message, (string) config('payment.esewa.secret_key'), true));
    }

    private function formatAmount(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }
}
