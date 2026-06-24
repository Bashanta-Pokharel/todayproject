@extends('layouts.frontend')

@section('title', 'Order Confirmed')

@section('content')
@php($order = $data['order'])
<section class="status-page">
    <div class="status-card success">
        <span class="status-icon">OK</span>
        <h1>Order confirmed</h1>
        <p>Your order <strong>{{ $order->order_number }}</strong> has been placed successfully.</p>
        <div class="order-total-block">
            <div class="order-total-row"><span>Payment</span><span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span></div>
            <div class="order-total-row"><span>Status</span><span>{{ ucfirst($order->order_status) }}</span></div>
            <div class="order-total-row final"><span>Total</span><span>Rs. {{ number_format($order->grand_total, 2) }}</span></div>
        </div>
        <div class="hero-actions">
            <a class="btn-primary" href="{{ route('customer.dashboard') }}">View dashboard</a>
            <a class="btn-outline" href="{{ route('frontend.index') }}">Continue shopping</a>
        </div>
    </div>
</section>
@endsection
