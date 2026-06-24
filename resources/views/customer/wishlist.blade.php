@extends('layouts.frontend')

@section('title', 'Wishlist')

@section('content')
<section class="products-section">
    <div class="section-header">
        <div>
            <div class="section-title">Wishlist</div>
            <div class="section-sub">{{ $data['items']->count() }} saved item(s)</div>
        </div>
    </div>

    <div class="product-grid">
        @forelse($data['items'] as $wishlist)
            @include('frontend.partials.product-card', ['product' => $wishlist->product])
        @empty
            <div class="empty-state">Your wishlist is empty.</div>
        @endforelse
    </div>
</section>
@endsection
