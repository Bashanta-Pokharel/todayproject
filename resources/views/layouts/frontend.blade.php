<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet" />
    <link href="{{asset('assets/frontend/style.css')}}" rel="stylesheet" />
</head>
<body>
<!-- ==================== -->
<!--   NAVIGATION (shared) -->
<!-- ==================== -->
<nav>
    <div class="nav-logo">Ligne</div>
    <div class="nav-links">
        <a href="{{route('frontend.index')}}">Home</a>
        <!-- limit to 5
          -->

       @foreach($data['categories']->take(7) as $category)
    <a href="{{ route('frontend.listing', $category->slug) }}" class="active">
        {{ $category->title }}
    </a>
@endforeach

    </div>
    <div class="nav-right">
        <div class="search-box">
            <svg width="13" height="13" viewBox="0 0 16 16" fill="none">
                <circle cx="6.5" cy="6.5" r="5" stroke="currentColor" stroke-width="1.3" />
                <path d="M10.5 10.5L14 14" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" />
            </svg>
            <input type="text" placeholder="Search styles…" />
        </div>
        <div class="nav-cart">
            Cart <span class="cart-badge">3</span>
        </div>
    </div>
</nav>
<!-- ============================== -->
<!--   PAGE 1 — HOME               -->
<!-- ============================== -->
@yield('content')
<!-- FOOTER -->
<footer class="footer">
    <div>
        <div class="footer-brand">Ligne</div>
        <div class="footer-desc">Elevated basics for modern life. We design with intention, source with care, and
            ship to
            40+ countries.</div>
    </div>
    <div>
        <div class="footer-col-title">Shop</div>
        <div class="footer-links">
            <a href="#">New Arrivals</a>
            <a href="#">Tops</a>
            <a href="#">Bottoms</a>
            <a href="#">Outerwear</a>
            <a href="#">Accessories</a>
        </div>
    </div>
    <div>
        <div class="footer-col-title">Help</div>
        <div class="footer-links">
            <a href="#">Shipping & Returns</a>
            <a href="#">Size Guide</a>
            <a href="#">FAQ</a>
            <a href="#">Contact Us</a>
        </div>
    </div>
    <div>
        <div class="footer-col-title">Company</div>
        <div class="footer-links">
            <a href="#">Our Story</a>
            <a href="#">Sustainability</a>
            <a href="#">Careers</a>
            <a href="#">Press</a>
        </div>
    </div>
</footer>
<div class="footer-bottom">
    <span>© 2026 Ligne. All rights reserved.</span>
    <span>Privacy · Terms · Accessibility</span>
</div>
</body>

</html>
