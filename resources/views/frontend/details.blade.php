@extends('layouts.frontend')

@section('title', $data['product']->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($data['product']->description ?? ''), 150))

@section('content')
@php
    $product = $data['product'];
    $firstImage = $product->images->first();
    $reviewAverage = round((float) $product->approvedReviews->avg('rating'), 1);
@endphp

<div class="breadcrumb">
    <a href="{{ route('frontend.index') }}">Home</a>
    <span>/</span>
    <a href="{{ route('frontend.listing', $product->category->slug) }}">{{ $product->category->title }}</a>
    <span>/</span>
    <span>{{ $product->title }}</span>
</div>

<section class="detail-layout">
    <div class="detail-gallery">
        @if($firstImage)
            <img id="mainImage" src="{{ asset('uploads/products/'.$firstImage->image_name) }}" class="main-product-image" alt="{{ $product->title }}">
        @else
            <div class="empty-detail-image">No image available</div>
        @endif

        @if($product->images->count() > 1)
            <div class="gallery-thumbs">
                @foreach($product->images as $key => $image)
                    <button type="button" class="gallery-thumb-button" data-image="{{ asset('uploads/products/'.$image->image_name) }}">
                        <img src="{{ asset('uploads/products/'.$image->image_name) }}" class="gallery-thumb-image {{ $key === 0 ? 'active' : '' }}" alt="{{ $image->image_title ?? $product->title }}">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <div class="detail-info-panel">
        <div>
            <div class="detail-eyebrow">{{ $product->category->title }}</div>
            <h1 class="detail-name">{{ $product->title }}</h1>
        </div>

        <div class="detail-price-row">
            <span class="detail-price">Rs. {{ number_format($product->sale_price, 2) }}</span>
            @if($product->discount > 0)
                <span class="detail-old-price">Rs. {{ number_format($product->price, 2) }}</span>
            @endif
        </div>

        <div class="detail-rating">
            <span class="stars">{{ str_repeat('*', max(1, (int) round($reviewAverage ?: 5))) }}</span>
            <span>{{ $reviewAverage > 0 ? $reviewAverage.' average rating' : 'No reviews yet' }}</span>
        </div>

        <div class="stock-pill {{ $product->is_in_stock ? 'in-stock' : 'out-stock' }}">
            {{ $product->is_in_stock ? $product->quantity.' in stock' : 'Out of stock' }}
        </div>

        <div class="divider"></div>

        <div class="detail-desc-text">
            {!! nl2br(e(strip_tags($product->description ?? 'No description available.'))) !!}
        </div>

        <form action="{{ route('frontend.add_to_cart') }}" method="POST" class="detail-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            @foreach($product->attributes as $attribute)
                <div>
                    <label class="detail-section-label" for="attribute-{{ $attribute->id }}">{{ $attribute->title }}</label>
                    <select class="form-control" id="attribute-{{ $attribute->id }}" name="attribute[{{ $attribute->id }}]">
                        <option value="">Select {{ $attribute->title }}</option>
                        @foreach(collect(explode(',', (string) $attribute->pivot->values))->map(fn ($value) => trim($value))->filter() as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach

            <div>
                <label class="detail-section-label" for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ max(1, $product->quantity) }}" class="form-control">
            </div>

            <div class="action-row">
                <button type="submit" class="btn-full" @disabled(! $product->is_in_stock)>Add to cart</button>
                @auth('customer')
                    <button formaction="{{ route('customer.wishlist.toggle', $product) }}" formmethod="POST" class="btn-wish-full" type="submit">Wish</button>
                @else
                    <a href="{{ route('customer.login') }}" class="btn-wish-full">Wish</a>
                @endauth
            </div>
        </form>
    </div>
</section>

<section class="reviews-section">
    <div class="section-header">
        <div class="section-title">Customer reviews</div>
    </div>

    @auth('customer')
        <form class="review-form" method="POST" action="{{ route('customer.reviews.store', $product) }}">
            @csrf
            <select name="rating" required>
                <option value="">Rating</option>
                @for($rating = 5; $rating >= 1; $rating--)
                    <option value="{{ $rating }}">{{ $rating }} star</option>
                @endfor
            </select>
            <input type="text" name="title" placeholder="Review title">
            <textarea name="body" rows="3" placeholder="Share your experience"></textarea>
            <button class="btn-primary" type="submit">Submit review</button>
        </form>
    @endauth

    <div class="review-list">
        @forelse($product->approvedReviews as $review)
            <article class="review-card">
                <strong>{{ $review->title ?? 'Customer review' }}</strong>
                <div class="stars">{{ str_repeat('*', $review->rating) }}</div>
                <p>{{ $review->body }}</p>
                <small>{{ $review->customer->name }}</small>
            </article>
        @empty
            <div class="empty-state">No approved reviews yet.</div>
        @endforelse
    </div>
</section>

<section class="related-section">
    <div class="section-header">
        <div class="section-title">You may also like</div>
        <a href="{{ route('frontend.listing', $product->category->slug) }}" class="section-link">View category</a>
    </div>
    <div class="product-grid">
        @forelse($data['relatedProducts'] as $relatedProduct)
            @include('frontend.partials.product-card', ['product' => $relatedProduct])
        @empty
            <div class="empty-state">No related products yet.</div>
        @endforelse
    </div>
</section>

<script>
    document.querySelectorAll('.gallery-thumb-button').forEach((button) => {
        button.addEventListener('click', () => {
            const mainImage = document.getElementById('mainImage');
            if (!mainImage) {
                return;
            }

            mainImage.src = button.dataset.image;
            document.querySelectorAll('.gallery-thumb-image').forEach((image) => image.classList.remove('active'));
            button.querySelector('img')?.classList.add('active');
        });
    });
</script>
@endsection
