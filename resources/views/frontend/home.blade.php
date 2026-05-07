@extends('layouts.frontend')
@section('title','Home Page')
@section('content')
    <div class="promo-banner">
        <span>Free shipping</span> on all orders over $150 · Use code <strong>NEWSEASON</strong>
    </div>

    <!-- HERO -->
    <div class="hero">
        <div class="hero-text">
            <div class="hero-eyebrow">New Arrival — SS 2026</div>
            <div class="hero-h">Effortless<br><em>everyday</em><br>wear.</div>
            <div class="hero-sub">Minimal silhouettes, elevated fabrics. Designed for how you actually live — from
                morning
                coffee to long evenings.</div>
            <div class="hero-actions">
                <button class="btn-primary">Shop collection</button>
                <button class="btn-outline">Our story</button>
            </div>
        </div>
        <div class="hero-visual">🧥</div>
    </div>

    <!-- CATEGORIES -->
    <div class="categories-section">
        <div class="section-header">
            <div class="section-title">Shop by category</div>
            <a href="#" class="section-link">View all →</a>
        </div>
        <div class="category-grid">
            <div class="category-card">
                <div class="cat-emoji">👔</div>
                <div class="cat-name">Tops</div>
                <div class="cat-count">24 styles</div>
            </div>
            <div class="category-card">
                <div class="cat-emoji">👖</div>
                <div class="cat-name">Bottoms</div>
                <div class="cat-count">18 styles</div>
            </div>
            <div class="category-card">
                <div class="cat-emoji">🧥</div>
                <div class="cat-name">Outerwear</div>
                <div class="cat-count">12 styles</div>
            </div>
            <div class="category-card">
                <div class="cat-emoji">👜</div>
                <div class="cat-name">Accessories</div>
                <div class="cat-count">20 styles</div>
            </div>
        </div>
    </div>

    <!-- NEW ARRIVALS -->
    <div class="products-section">
        <div class="section-header">
            <div class="section-title">New arrivals</div>
            <a href="#" class="section-link">View all →</a>
        </div>
        <div class="filter-pills">
            <button class="pill active">All</button>
            <button class="pill">Tops</button>
            <button class="pill">Bottoms</button>
            <button class="pill">Outerwear</button>
        </div>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-thumb">
                    <div class="product-badge">New</div>
                    <div class="product-wish">♡</div>
                    👔
                </div>
                <div class="product-name">Linen Shirt</div>
                <div class="product-cat">Tops</div>
                <div class="product-footer">
                    <div><span class="product-price">$89</span></div>
                    <button class="add-to-cart">+ Add</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-thumb">
                    <div class="product-badge sale">Sale</div>
                    <div class="product-wish">♡</div>
                    👖
                </div>
                <div class="product-name">Wide-Leg Trousers</div>
                <div class="product-cat">Bottoms</div>
                <div class="product-footer">
                    <div><span class="product-price">$129</span><span class="product-old-price">$159</span></div>
                    <button class="add-to-cart">+ Add</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-thumb">
                    <div class="product-badge">New</div>
                    <div class="product-wish">♡</div>
                    🧥
                </div>
                <div class="product-name">Wool Overcoat</div>
                <div class="product-cat">Outerwear</div>
                <div class="product-footer">
                    <div><span class="product-price">$295</span></div>
                    <button class="add-to-cart">+ Add</button>
                </div>
            </div>
            <div class="product-card">
                <div class="product-thumb">
                    <div class="product-wish">♡</div>
                    👕
                </div>
                <div class="product-name">Ribbed Tank</div>
                <div class="product-cat">Tops</div>
                <div class="product-footer">
                    <div><span class="product-price">$45</span></div>
                    <button class="add-to-cart">+ Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDITORIAL STRIP -->
    <div class="editorial-strip">
        <div class="editorial-img">👘</div>
        <div class="editorial-text">
            <div class="editorial-label">The Edit — Spring 2026</div>
            <div class="editorial-h">Soft, light,<br>intentional.</div>
            <div class="editorial-sub">This season we focused on natural fibres, relaxed forms, and lasting design.
                Pieces
                that don't try too hard — and last for years.</div>
            <button class="btn-outline" style="margin-top:.5rem;">Read the story</button>
        </div>
    </div>

@endsection

