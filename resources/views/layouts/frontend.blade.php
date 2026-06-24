<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Shop curated products with secure checkout, order tracking, and fast delivery.')">
    <title>@yield('title', 'Ligne Store')</title>
    <link href="{{ asset('assets/frontend/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="promo-banner">
    Secure checkout, verified payments, and free delivery on orders over Rs. 5,000.
</div>

<nav class="site-nav">
    <a class="nav-logo" href="{{ route('frontend.index') }}">Ligne</a>

    <div class="nav-links">
        <a href="{{ route('frontend.index') }}" @class(['active' => request()->routeIs('frontend.index')])>Shop</a>
        @foreach(collect($data['categories'] ?? [])->take(4) as $category)
            <a href="{{ route('frontend.listing', $category->slug) }}" @class(['active' => request()->is('listing/'.$category->slug)])>
                {{ $category->title }}
            </a>
        @endforeach
    </div>

    <div class="nav-right">
        <form class="search-box" action="{{ route('frontend.index') }}" method="GET">
            <span aria-hidden="true">⌕</span>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products">
        </form>

        <button class="theme-toggle" type="button" data-theme-toggle aria-label="Toggle dark mode">◐</button>

        <a class="nav-cart" href="{{ route('frontend.cart') }}">
            Cart <span class="cart-badge">{{ \Cart::getTotalQuantity() }}</span>
        </a>

        <div class="nav-auth">
            @auth('customer')
                <a href="{{ route('customer.dashboard') }}">{{ auth('customer')->user()->name }}</a>
                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            @else
                <a href="{{ route('customer.login') }}">Login</a>
                <a href="{{ route('customer.register') }}" class="nav-cta">Register</a>
            @endauth
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="flash flash-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="flash flash-error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<main>
    @yield('content')
</main>

<footer class="footer">
    <div>
        <div class="footer-brand">Ligne</div>
        <div class="footer-desc">A modern commerce experience with curated products, secure payments, and thoughtful service.</div>
    </div>
    <div>
        <div class="footer-col-title">Shop</div>
        <div class="footer-links">
            <a href="{{ route('frontend.index') }}">All Products</a>
            <a href="{{ route('frontend.cart') }}">Cart</a>
            @auth('customer')
                <a href="{{ route('customer.wishlist') }}">Wishlist</a>
                <a href="{{ route('customer.dashboard') }}">Orders</a>
            @endauth
        </div>
    </div>
    <div>
        <div class="footer-col-title">Help</div>
        <div class="footer-links">
            <a href="{{ route('frontend.index') }}#faq">FAQ</a>
            <a href="{{ route('frontend.index') }}#contact">Contact</a>
            <a href="{{ route('frontend.index') }}#about">About Us</a>
        </div>
    </div>
    <div>
        <div class="footer-col-title">Newsletter</div>
        <form class="newsletter-form" action="{{ route('frontend.index') }}" method="GET">
            <input type="email" placeholder="Email address" aria-label="Email address">
            <button type="submit">Join</button>
        </form>
    </div>
</footer>
<div class="footer-bottom">
    <span>© {{ date('Y') }} Ligne. All rights reserved.</span>
    <span>Privacy · Terms · Accessibility</span>
</div>

<script>
    const toggle = document.querySelector('[data-theme-toggle]');
    const storedTheme = localStorage.getItem('theme');

    if (storedTheme === 'dark') {
        document.documentElement.dataset.theme = 'dark';
    }

    toggle?.addEventListener('click', () => {
        const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
        document.documentElement.dataset.theme = nextTheme;
        localStorage.setItem('theme', nextTheme);
    });
</script>
</body>
</html>
