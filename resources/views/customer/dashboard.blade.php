@extends('layouts.frontend')

@section('title', 'Customer Dashboard')

@section('content')
<section class="dashboard-page">
    <div class="dashboard-header">
        <div>
            <div class="hero-eyebrow">Welcome back</div>
            <h1>{{ auth('customer')->user()->name }}</h1>
            <p>Track orders, manage your wishlist, and continue shopping from your dashboard.</p>
        </div>
        <div class="hero-actions">
            <a class="btn-primary" href="{{ route('frontend.index') }}">Shop products</a>
            <a class="btn-outline" href="{{ route('customer.wishlist') }}">Wishlist</a>
        </div>
    </div>

    <div class="dashboard-grid">
        <section class="dashboard-panel">
            <div class="section-header">
                <div class="section-title">Recent orders</div>
            </div>
            <div class="order-list">
                @forelse($data['orders'] as $order)
                    <article class="order-card">
                        <div>
                            <strong>{{ $order->order_number }}</strong>
                            <span>{{ $order->items->count() }} item(s)</span>
                        </div>
                        <div>Rs. {{ number_format($order->grand_total, 2) }}</div>
                        <div>{{ ucfirst($order->order_status) }}</div>
                    </article>
                @empty
                    <div class="empty-state">No orders yet.</div>
                @endforelse
            </div>
        </section>

        <section class="dashboard-panel">
            <div class="section-header">
                <div class="section-title">Recommended</div>
            </div>
            <div class="mini-product-list">
                @foreach($data['products']->take(4) as $product)
                    <a href="{{ route('frontend.details', $product->slug) }}" class="mini-product">
                        @if($product->images->first())
                            <img src="{{ asset('uploads/products/'.$product->images->first()->image_name) }}" alt="{{ $product->title }}">
                        @endif
                        <span>{{ $product->title }}</span>
                        <strong>Rs. {{ number_format($product->sale_price, 2) }}</strong>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</section>
@endsection
