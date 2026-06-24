@extends('layouts.frontend')

@section('title', 'Checkout')

@section('content')
@php
    $summary = $data['summary'];
    $customer = auth('customer')->user();
    $defaultPaymentMethod = array_key_first($data['paymentMethods']);
@endphp

<section class="checkout-page">
    <div class="checkout-nav">
        <div class="checkout-steps">
            <div class="checkout-step done"><div class="step-num">1</div> Cart</div>
            <div class="step-arrow">/</div>
            <div class="checkout-step active"><div class="step-num">2</div> Checkout</div>
            <div class="step-arrow">/</div>
            <div class="checkout-step"><div class="step-num">3</div> Payment</div>
        </div>
        <div class="secure-note">Secure checkout</div>
    </div>

    <form class="checkout-body" method="POST" action="{{ route('customer.checkout.store') }}">
        @csrf
        <div class="checkout-form-area">
            <div>
                <div class="form-section-h">Contact</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Full name</label>
                        <input class="form-input" name="customer_name" value="{{ old('customer_name', $customer->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input class="form-input" type="email" name="customer_email" value="{{ old('customer_email', $customer->email) }}" required>
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Phone</label>
                        <input class="form-input" name="customer_phone" value="{{ old('customer_phone', $customer->phone) }}">
                    </div>
                </div>
            </div>

            <div>
                <div class="form-section-h">Shipping</div>
                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label">Address</label>
                        <input class="form-input" name="shipping_address" value="{{ old('shipping_address') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input class="form-input" name="shipping_city" value="{{ old('shipping_city') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">State</label>
                        <input class="form-input" name="shipping_state" value="{{ old('shipping_state') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Postal code</label>
                        <input class="form-input" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <input class="form-input" name="shipping_country" value="{{ old('shipping_country', 'Nepal') }}" required>
                    </div>
                </div>
            </div>

            <div>
                <div class="form-section-h">Payment</div>
                <div class="payment-method-grid">
                    @foreach($data['paymentMethods'] as $value => $label)
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method', $defaultPaymentMethod) === $value)>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Order notes</label>
                <textarea class="form-input" name="notes" rows="3">{{ old('notes') }}</textarea>
            </div>

            <button class="btn-primary full-width" type="submit">Place order - Rs. {{ number_format($summary['grand_total'], 2) }}</button>
        </div>

        <aside class="checkout-sidebar">
            <div class="order-review-title">Order review</div>
            @foreach($summary['items'] as $item)
                <div class="order-line">
                    <div class="order-line-img">
                        @if($item['product']->images->first())
                            <img src="{{ asset('uploads/products/'.$item['product']->images->first()->image_name) }}" alt="{{ $item['product']->title }}">
                        @else
                            <span>No image</span>
                        @endif
                        <div class="order-qty-badge">{{ $item['quantity'] }}</div>
                    </div>
                    <div style="flex:1;">
                        <div class="order-line-name">{{ $item['product']->title }}</div>
                        <div class="order-line-meta">{{ collect($item['attributes'])->map(fn ($value, $key) => $key.': '.$value)->implode(', ') }}</div>
                    </div>
                    <div class="order-line-price">Rs. {{ number_format($item['line_total'], 2) }}</div>
                </div>
            @endforeach
            <div class="order-total-block">
                <div class="order-total-row"><span>Subtotal</span><span>Rs. {{ number_format($summary['subtotal'], 2) }}</span></div>
                <div class="order-total-row"><span>Shipping</span><span>Rs. {{ number_format($summary['shipping_total'], 2) }}</span></div>
                <div class="order-total-row"><span>Discount</span><span>Rs. {{ number_format($summary['discount_total'], 2) }}</span></div>
                <div class="order-total-row final"><span>Total</span><span>Rs. {{ number_format($summary['grand_total'], 2) }}</span></div>
            </div>
        </aside>
    </form>
</section>
@endsection
