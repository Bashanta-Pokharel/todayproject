@extends('layouts.frontend')

@section('title', 'Order Confirmed')

@section('content')
@php
    $order = $data['order'];
    $paymentDetails = $data['paymentMethodDetails'][$order->payment_method] ?? null;
@endphp
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
        @if(in_array($order->payment_method, ['bank_transfer', 'qr_payment'], true) && $paymentDetails)
            <div class="manual-payment-note">
                <strong>{{ $paymentDetails['label'] }}</strong>
                <span>{{ $paymentDetails['description'] }}</span>
                @if(! empty($paymentDetails['meta']))
                    <small>{{ $paymentDetails['meta'] }}</small>
                @endif
            </div>
        @endif
        <div class="hero-actions">
            <a class="btn-primary" href="{{ route('customer.dashboard') }}">View dashboard</a>
            <a class="btn-outline" href="{{ route('frontend.index') }}">Continue shopping</a>
        </div>
    </div>
</section>
@endsection
