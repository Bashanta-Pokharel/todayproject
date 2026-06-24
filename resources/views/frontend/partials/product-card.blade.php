@php
    $image = $product->images->first();
    $rating = round((float) ($product->approved_reviews_avg_rating ?? 0), 1);
@endphp

<article class="product-card">
    <a href="{{ route('frontend.details', $product->slug) }}" class="product-card-link">
        <div class="product-thumb">
            @if($image)
                <img src="{{ asset('uploads/products/'.$image->image_name) }}" alt="{{ $product->title }}" loading="lazy">
            @else
                <div class="empty-thumb">No image</div>
            @endif
            @if($product->discount > 0)
                <div class="product-badge sale">Sale</div>
            @elseif($product->created_at?->gt(now()->subDays(14)))
                <div class="product-badge">New</div>
            @endif
        </div>
        <div class="product-name">{{ $product->title }}</div>
        <div class="product-cat">{{ $product->category?->title ?? 'Uncategorized' }}</div>
        <div class="rating-row">
            <span class="stars">{{ str_repeat('*', max(1, (int) round($rating ?: 5))) }}</span>
            <span>{{ $rating > 0 ? $rating : 'New' }}</span>
        </div>
    </a>

    <div class="product-footer">
        <div>
            <span class="product-price">Rs. {{ number_format($product->sale_price, 2) }}</span>
            @if($product->discount > 0)
                <span class="product-old-price">Rs. {{ number_format($product->price, 2) }}</span>
            @endif
        </div>
        <form method="POST" action="{{ route('frontend.add_to_cart') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button class="add-to-cart" type="submit" @disabled(! $product->is_in_stock)>
                {{ $product->is_in_stock ? '+ Add' : 'Sold out' }}
            </button>
        </form>
    </div>
</article>
