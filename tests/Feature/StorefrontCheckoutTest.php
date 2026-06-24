<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StorefrontCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_product_catalog(): void
    {
        $product = $this->product();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee($product->title);
    }

    public function test_storefront_navigation_only_shows_shop_and_first_four_categories(): void
    {
        $admin = User::factory()->admin()->create();

        foreach (range(1, 6) as $rank) {
            Category::create([
                'title' => "Category {$rank}",
                'slug' => "category-{$rank}",
                'rank' => $rank,
                'status' => true,
                'created_by' => $admin->id,
            ]);
        }

        $response = $this->get('/');
        preg_match('/<div class="nav-links">(.*?)<\/div>/s', $response->getContent(), $matches);
        $navLinks = $matches[1] ?? '';

        $this->assertSame(5, substr_count($navLinks, '<a '));
        $this->assertStringContainsString('Category 1', $navLinks);
        $this->assertStringContainsString('Category 4', $navLinks);
        $this->assertStringNotContainsString('Category 5', $navLinks);
        $this->assertStringNotContainsString('Category 6', $navLinks);
    }

    public function test_customer_can_add_product_to_cart_and_checkout_with_cash_on_delivery(): void
    {
        $customer = Customer::factory()->create();
        $product = $this->product(quantity: 5, price: 1200);

        $this->post(route('frontend.add_to_cart'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertSessionHasNoErrors();

        $response = $this->actingAs($customer, 'customer')->post(route('customer.checkout.store'), [
            'customer_name' => 'Test Customer',
            'customer_email' => 'customer@example.com',
            'customer_phone' => '9800000000',
            'shipping_address' => 'Kathmandu Mall',
            'shipping_city' => 'Kathmandu',
            'shipping_state' => 'Bagmati',
            'shipping_postal_code' => '44600',
            'shipping_country' => 'Nepal',
            'payment_method' => 'cash_on_delivery',
        ]);

        $order = Order::first();

        $response->assertRedirect(route('payments.success', $order->order_number));
        $this->assertSame('confirmed', $order->fresh()->order_status);
        $this->assertSame(3, $product->fresh()->quantity);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_disabled_online_payment_method_is_rejected_before_order_is_created(): void
    {
        config([
            'payment.paypal.enabled' => false,
            'payment.paypal.client_id' => null,
            'payment.paypal.client_secret' => null,
        ]);

        $customer = Customer::factory()->create();
        $product = $this->product(quantity: 5, price: 1200);

        $this->post(route('frontend.add_to_cart'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertSessionHasNoErrors();

        $this->actingAs($customer, 'customer')
            ->post(route('customer.checkout.store'), $this->checkoutPayload('paypal'))
            ->assertSessionHasErrors('payment_method');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(5, $product->fresh()->quantity);
    }

    public function test_customer_can_complete_esewa_payment_with_signed_callback(): void
    {
        $customer = Customer::factory()->create();
        $product = $this->product(quantity: 5, price: 1200);

        $this->post(route('frontend.add_to_cart'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertSessionHasNoErrors();

        $this->actingAs($customer, 'customer')
            ->post(route('customer.checkout.store'), $this->checkoutPayload('esewa'))
            ->assertOk()
            ->assertViewIs('frontend.payment-redirect');

        $order = Order::firstOrFail();

        $this->assertSame('pending', $order->payment_status);
        $this->assertSame(3, $product->fresh()->quantity);

        $this->get(route('payments.esewa.success', [
            'data' => base64_encode(json_encode($this->signedEsewaPayload($order), JSON_THROW_ON_ERROR)),
        ]))->assertRedirect(route('payments.success', $order->order_number));

        $this->assertSame('paid', $order->fresh()->payment_status);
        $this->assertSame('confirmed', $order->fresh()->order_status);
        $this->assertDatabaseHas('payment_transactions', [
            'order_id' => $order->id,
            'provider' => 'esewa',
            'reference' => $order->order_number,
            'transaction_code' => 'ESEWA-TEST',
            'status' => 'paid',
        ]);
    }

    public function test_esewa_callback_with_mismatched_amount_cancels_order_and_restores_stock(): void
    {
        $customer = Customer::factory()->create();
        $product = $this->product(quantity: 5, price: 1200);

        $this->post(route('frontend.add_to_cart'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertSessionHasNoErrors();

        $this->actingAs($customer, 'customer')
            ->post(route('customer.checkout.store'), $this->checkoutPayload('esewa'))
            ->assertOk();

        $order = Order::firstOrFail();

        $payload = $this->signedEsewaPayload($order, [
            'total_amount' => number_format((float) $order->grand_total - 1, 2, '.', ''),
        ]);

        $this->get(route('payments.esewa.success', [
            'data' => base64_encode(json_encode($payload, JSON_THROW_ON_ERROR)),
        ]))->assertRedirect(route('payments.failure', ['order' => $order->order_number, 'gateway' => 'esewa']));

        $this->assertSame('cancelled', $order->fresh()->order_status);
        $this->assertSame('failed', $order->fresh()->payment_status);
        $this->assertSame(5, $product->fresh()->quantity);
    }

    public function test_stripe_success_requires_matching_session_amount(): void
    {
        config([
            'payment.stripe.enabled' => true,
            'payment.stripe.secret_key' => 'sk_test_123',
            'payment.stripe.currency' => 'usd',
        ]);

        Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.test/pay',
            ]),
            'https://api.stripe.com/v1/checkout/sessions/cs_test_123' => Http::response([
                'id' => 'cs_test_123',
                'payment_status' => 'paid',
                'client_reference_id' => null,
                'currency' => 'usd',
                'amount_total' => 100,
                'payment_intent' => 'pi_test_123',
            ]),
        ]);

        $customer = Customer::factory()->create();
        $product = $this->product(quantity: 5, price: 1200);

        $this->post(route('frontend.add_to_cart'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertSessionHasNoErrors();

        $this->actingAs($customer, 'customer')
            ->post(route('customer.checkout.store'), $this->checkoutPayload('stripe'))
            ->assertRedirect('https://checkout.stripe.test/pay');

        $order = Order::firstOrFail();

        $this->assertDatabaseHas('payment_transactions', [
            'order_id' => $order->id,
            'provider' => 'stripe',
            'reference' => 'cs_test_123',
            'status' => 'pending',
        ]);

        $this->get(route('payments.stripe.success', [
            'order' => $order,
            'session_id' => 'cs_test_123',
        ]))->assertRedirect(route('payments.failure', ['order' => $order->order_number, 'gateway' => 'stripe']));

        $this->assertSame('cancelled', $order->fresh()->order_status);
        $this->assertSame('failed', $order->fresh()->payment_status);
        $this->assertSame(5, $product->fresh()->quantity);
    }

    public function test_admin_routes_require_admin_role(): void
    {
        $customerUser = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($customerUser)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    private function product(int $quantity = 10, int $price = 1000): Product
    {
        $admin = User::factory()->admin()->create();
        $category = Category::create([
            'title' => 'Accessories',
            'slug' => 'accessories',
            'rank' => 1,
            'status' => true,
            'created_by' => $admin->id,
        ]);

        return Product::create([
            'category_id' => $category->id,
            'title' => 'Everyday Tote',
            'slug' => 'everyday-tote',
            'quantity' => $quantity,
            'price' => $price,
            'discount' => 0,
            'description' => 'A durable daily carry product.',
            'status' => true,
            'created_by' => $admin->id,
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function checkoutPayload(string $paymentMethod): array
    {
        return [
            'customer_name' => 'Test Customer',
            'customer_email' => 'customer@example.com',
            'customer_phone' => '9800000000',
            'shipping_address' => 'Kathmandu Mall',
            'shipping_city' => 'Kathmandu',
            'shipping_state' => 'Bagmati',
            'shipping_postal_code' => '44600',
            'shipping_country' => 'Nepal',
            'payment_method' => $paymentMethod,
        ];
    }

    /**
     * @param  array<string, string>  $overrides
     * @return array<string, string>
     */
    private function signedEsewaPayload(Order $order, array $overrides = []): array
    {
        $payload = array_merge([
            'transaction_code' => 'ESEWA-TEST',
            'status' => 'COMPLETE',
            'total_amount' => number_format((float) $order->grand_total, 2, '.', ''),
            'transaction_uuid' => $order->order_number,
            'product_code' => (string) config('payment.esewa.product_code'),
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
        ], $overrides);

        $message = collect(explode(',', $payload['signed_field_names']))
            ->map(fn (string $field): string => "{$field}={$payload[$field]}")
            ->implode(',');

        $payload['signature'] = base64_encode(hash_hmac('sha256', $message, (string) config('payment.esewa.secret_key'), true));

        return $payload;
    }
}
