@extends('layouts.frontend')

@section('title', 'Shopping Cart')

@section('content')
@php($summary = $data['summary'])

<section class="cart-page">
    <div class="cart-panel">
        <div class="section-header">
            <div>
                <div class="section-title">Shopping cart</div>
                <div class="section-sub">{{ \Cart::getTotalQuantity() }} item(s)</div>
            </div>
            @if($summary['items']->isNotEmpty())
                <form method="POST" action="{{ route('frontend.cart.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn-outline" type="submit">Clear cart</button>
                </form>
            @endif
        </div>

        <div class="cart-lines">
            @forelse($summary['items'] as $item)
                @php($product = $item['product'])
                <article class="cart-line">
                    <a class="cart-line-img" href="{{ route('frontend.details', $product->slug) }}">
                        @if($product->images->first())
                            <img src="{{ asset('uploads/products/'.$product->images->first()->image_name) }}" alt="{{ $product->title }}">
                        @else
                            <span>No image</span>
                        @endif
                    </a>
                    <div class="cart-line-info">
                        <a class="cart-line-name" href="{{ route('frontend.details', $product->slug) }}">{{ $product->title }}</a>
                        <div class="cart-line-meta">
                            @forelse($item['attributes'] as $key => $attribute)
                                {{ $key }}: {{ $attribute }}<br>
                            @empty
                                {{ $product->category?->title }}
                            @endforelse
                        </div>
                        <form class="cart-line-qty" method="POST" action="{{ route('frontend.cart.update', $product) }}">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $product->quantity }}">
                            <button class="cqbtn" type="submit">Update</button>
                        </form>
                    </div>
                    <div class="cart-line-price">
                        Rs. {{ number_format($item['line_total'], 2) }}
                        <form method="POST" action="{{ route('frontend.cart.remove', $product) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="link-button">Remove</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="empty-state">Your cart is empty.</div>
            @endforelse
        </div>
    </div>

    <aside class="cart-summary">
        <h2>Order summary</h2>

        <form class="promo-input-row" method="POST" action="{{ route('frontend.cart.coupon.apply') }}">
            @csrf
            <input class="promo-field" type="text" name="coupon_code" value="{{ session('coupon_code') }}" placeholder="Coupon code">
            <button class="promo-apply" type="submit">Apply</button>
        </form>

        @if($summary['coupon'])
            <form method="POST" action="{{ route('frontend.cart.coupon.remove') }}">
                @csrf
                @method('DELETE')
                <button class="link-button" type="submit">Remove {{ $summary['coupon']->code }}</button>
            </form>
        @endif

        <div class="order-total-block">
            <div class="order-total-row"><span>Subtotal</span><span>Rs. {{ number_format($summary['subtotal'], 2) }}</span></div>
            <div class="order-total-row"><span>Discount</span><span>Rs. {{ number_format($summary['discount_total'], 2) }}</span></div>
            <div class="order-total-row"><span>Shipping</span><span>Rs. {{ number_format($summary['shipping_total'], 2) }}</span></div>
            <div class="order-total-row final"><span>Total</span><span>Rs. {{ number_format($summary['grand_total'], 2) }}</span></div>
        </div>

        @auth('customer')
            <a href="{{ route('customer.checkout') }}" class="btn-primary full-width {{ $summary['items']->isEmpty() ? 'disabled' : '' }}">Checkout</a>
        @else
            <a href="{{ route('customer.login') }}" class="btn-primary full-width">Login to checkout</a>
        @endauth
        <a href="{{ route('frontend.index') }}" class="btn-outline full-width">Continue shopping</a>
    </aside>
</section>
@endsection
