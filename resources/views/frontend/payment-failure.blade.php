@extends('layouts.frontend')

@section('title', 'Payment Failed')

@section('content')
<section class="status-page">
    <div class="status-card failure">
        <span class="status-icon">!</span>
        <h1>Payment could not be completed</h1>
        <p>The payment was cancelled or failed verification. No paid order was recorded.</p>
        @if($data['order'])
            <p>Order reference: <strong>{{ $data['order']->order_number }}</strong></p>
        @endif
        <div class="hero-actions">
            <a class="btn-primary" href="{{ route('frontend.cart') }}">Return to cart</a>
            <a class="btn-outline" href="{{ route('frontend.index') }}">Continue shopping</a>
        </div>
    </div>
</section>
@endsection
