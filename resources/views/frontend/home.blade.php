@extends('layouts.frontend')

@section('title', 'B Commerce Mart')
@section('meta_description', 'Shop Bashanta BBB products with search, filters, secure checkout, wishlist, and order tracking.')

@section('content')
<section class="hero">
    <div class="hero-text">
        <div class="hero-eyebrow">Bashanta BBB Marketplace</div>
        <h1 class="hero-h">Bold gear, sharp deals, secure shopping.</h1>
        <p class="hero-sub">Explore B Commerce Mart with curated products, fast carting, wishlists, and checkout through eSewa, QR payment, bank transfer, cash on delivery, or enabled online wallets.</p>
        <div class="hero-actions">
            <a href="#products" class="btn-primary">Shop now</a>
            <a href="#about" class="btn-outline">About us</a>
        </div>
    </div>
    <div class="hero-visual">
        @if(($data['featuredProducts'] ?? collect())->first()?->images->first())
            <img src="{{ asset('uploads/products/'.$data['featuredProducts']->first()->images->first()->image_name) }}" alt="Featured product">
        @else
            <div class="hero-placeholder">BCM</div>
        @endif
    </div>
</section>

<section class="categories-section">
    <div class="section-header">
        <div class="section-title">Shop by category</div>
        <a href="#products" class="section-link">View products</a>
    </div>
    <div class="category-grid">
        @foreach($data['categories'] as $category)
            <a class="category-card" href="{{ route('frontend.listing', $category->slug) }}">
                <span class="cat-name">{{ $category->title }}</span>
                <span class="cat-count">{{ $category->products_count }} products</span>
            </a>
        @endforeach
    </div>
</section>

<section class="products-section" id="products">
    <div class="section-header">
        <div>
            <div class="section-title">All products</div>
            <div class="section-sub">{{ $data['products']->total() }} item(s) available</div>
        </div>
    </div>

    <form class="catalog-toolbar" method="GET" action="{{ route('frontend.index') }}">
        <input type="search" name="q" value="{{ $data['filters']['q'] ?? '' }}" placeholder="Search products">
        <input type="number" name="min_price" value="{{ $data['filters']['min_price'] ?? '' }}" placeholder="Min price" min="0">
        <input type="number" name="max_price" value="{{ $data['filters']['max_price'] ?? '' }}" placeholder="Max price" min="0">
        <select name="sort">
            <option value="">Featured</option>
            <option value="newest" @selected(($data['filters']['sort'] ?? '') === 'newest')>Newest</option>
            <option value="price_asc" @selected(($data['filters']['sort'] ?? '') === 'price_asc')>Price: low to high</option>
            <option value="price_desc" @selected(($data['filters']['sort'] ?? '') === 'price_desc')>Price: high to low</option>
        </select>
        <button class="btn-primary" type="submit">Filter</button>
    </form>

    <div class="product-grid">
        @forelse($data['products'] as $product)
            @include('frontend.partials.product-card', ['product' => $product])
        @empty
            <div class="empty-state">No products matched your filters.</div>
        @endforelse
    </div>

    <div class="pagination-wrap">{{ $data['products']->links() }}</div>
</section>

<section class="trust-band" id="about">
    <div>
        <h2>Built for confident shopping</h2>
        <p>Every B Commerce Mart order is stored with transaction history, stock movement, and customer details so support and fulfillment stay organized.</p>
    </div>
    <div class="trust-grid">
        <div><strong>Secure</strong><span>CSRF protected checkout and verified payment callbacks.</span></div>
        <div><strong>Responsive</strong><span>Designed for mobile, tablet, and desktop shopping.</span></div>
        <div><strong>Trackable</strong><span>Customers can see recent orders from their dashboard.</span></div>
    </div>
</section>

<section class="faq-section" id="faq">
    <div class="section-title">FAQ</div>
    <details open>
        <summary>Which payment methods are supported?</summary>
        <p>eSewa, QR payment, bank transfer, Cash on Delivery, and any enabled PayPal, Khalti, or Stripe checkout.</p>
    </details>
    <details>
        <summary>Can I track my orders?</summary>
        <p>Yes. Log in to your customer dashboard to view recent orders and statuses.</p>
    </details>
    <details id="contact">
        <summary>How do I contact support?</summary>
        <p>Send your order number and contact details through your preferred support channel.</p>
    </details>
</section>
@endsection
