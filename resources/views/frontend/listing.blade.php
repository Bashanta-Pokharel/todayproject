@extends('layouts.frontend')
@section('title','Listing Page')
@section('content')
<div class="page-label">Page 2 — Product Listing</div>
    <nav>
        <div class="nav-logo">Ligne</div>
        <div class="nav-links">
            <a href="#">All</a><a href="#" class="active">Tops</a><a href="#">Bottoms</a><a href="#">Outerwear</a><a
                href="#">Accessories</a>
        </div>
        <div class="nav-right">
            <div class="search-box">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none">
                    <circle cx="6.5" cy="6.5" r="5" stroke="currentColor" stroke-width="1.3" />
                    <path d="M10.5 10.5L14 14" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" />
                </svg>
                <input type="text" placeholder="Search styles…" />
            </div>
            <div class="nav-cart">Cart <span class="cart-badge">3</span></div>
        </div>
    </nav>

    <div class="listing-hero">
        <div>
            <div class="listing-h">Tops</div>
            <div style="font-size:13px;color:var(--charcoal-60);margin-top:4px;">24 items</div>
        </div>
        <div class="listing-meta-row">
            <button class="sort-btn">Sort: Featured ↕</button>
        </div>
    </div>

    <div class="listing-layout">
        <!-- SIDEBAR -->
        <div class="listing-sidebar">
            <div>
                <div class="sidebar-group-title">Category</div>
                <div class="sidebar-filters">
                    <div class="sidebar-filter-item">All <span class="count">72</span></div>
                    <div class="sidebar-filter-item active">Tops <span class="count">24</span></div>
                    <div class="sidebar-filter-item">Bottoms <span class="count">18</span></div>
                    <div class="sidebar-filter-item">Outerwear <span class="count">12</span></div>
                    <div class="sidebar-filter-item">Accessories <span class="count">18</span></div>
                </div>
            </div>
            <div>
                <div class="sidebar-group-title">Price</div>
                <div class="price-range">
                    <input class="price-input" type="text" placeholder="$0" />
                    <input class="price-input" type="text" placeholder="$500" />
                </div>
            </div>
            <div>
                <div class="sidebar-group-title">Size</div>
                <div class="size-grid">
                    <div class="size-chip">XS</div>
                    <div class="size-chip active">S</div>
                    <div class="size-chip active">M</div>
                    <div class="size-chip">L</div>
                    <div class="size-chip">XL</div>
                    <div class="size-chip">XXL</div>
                </div>
            </div>
            <div>
                <div class="sidebar-group-title">Colour</div>
                <div class="color-options" style="flex-wrap:wrap;gap:10px;margin-top:8px;">
                    <div class="color-dot selected" style="background:#e8e0d5;"></div>
                    <div class="color-dot" style="background:#2c2c28;"></div>
                    <div class="color-dot" style="background:#8b6f5c;"></div>
                    <div class="color-dot" style="background:#c4bba8;"></div>
                    <div class="color-dot" style="background:#b0b8a8;"></div>
                    <div class="color-dot" style="background:#d4c5b0;"></div>
                </div>
            </div>
            <div>
                <div class="sidebar-group-title">Availability</div>
                <div class="sidebar-filters">
                    <div class="sidebar-filter-item active">In stock <span class="count">20</span></div>
                    <div class="sidebar-filter-item">Pre-order <span class="count">4</span></div>
                </div>
            </div>
        </div>

        <!-- MAIN GRID -->
        <div class="listing-main">
            <div class="active-filters">
                <div class="active-filter-tag">Tops <span>×</span></div>
                <div class="active-filter-tag">Size: S, M <span>×</span></div>
                <div class="active-filter-tag">In stock <span>×</span></div>
                <div class="clear-all">Clear all</div>
            </div>
            <div class="listing-products">
                <div class="product-card">
                    <div class="product-thumb">
                        <div class="product-badge">New</div>
                        <div class="product-wish">♡</div>👔
                    </div>
                    <div class="product-name">Linen Shirt</div>
                    <div class="product-cat">Tops</div>
                    <div class="product-footer">
                        <div><span class="product-price">$89</span></div><button class="add-to-cart">+ Add</button>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-thumb">
                        <div class="product-badge sale">Sale</div>
                        <div class="product-wish">♡</div>🧶
                    </div>
                    <div class="product-name">Merino Cardigan</div>
                    <div class="product-cat">Tops</div>
                    <div class="product-footer">
                        <div><span class="product-price">$145</span><span class="product-old-price">$175</span></div>
                        <button class="add-to-cart">+ Add</button>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-thumb">
                        <div class="product-wish">♡</div>👕
                    </div>
                    <div class="product-name">Ribbed Tank</div>
                    <div class="product-cat">Tops</div>
                    <div class="product-footer">
                        <div><span class="product-price">$45</span></div><button class="add-to-cart">+ Add</button>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-thumb">
                        <div class="product-badge">New</div>
                        <div class="product-wish">♡</div>👘
                    </div>
                    <div class="product-name">Silk Blouse</div>
                    <div class="product-cat">Tops</div>
                    <div class="product-footer">
                        <div><span class="product-price">$165</span></div><button class="add-to-cart">+ Add</button>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-thumb">
                        <div class="product-wish">♡</div>👔
                    </div>
                    <div class="product-name">Oversized Tee</div>
                    <div class="product-cat">Tops</div>
                    <div class="product-footer">
                        <div><span class="product-price">$55</span></div><button class="add-to-cart">+ Add</button>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-thumb">
                        <div class="product-badge sale">Sale</div>
                        <div class="product-wish">♡</div>🧥
                    </div>
                    <div class="product-name">Cotton Blazer</div>
                    <div class="product-cat">Tops</div>
                    <div class="product-footer">
                        <div><span class="product-price">$198</span><span class="product-old-price">$240</span></div>
                        <button class="add-to-cart">+ Add</button>
                    </div>
                </div>
            </div>
            <div class="pagination">
                <button class="page-btn">‹</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">›</button>
            </div>
        </div>
    </div>
@endsection
