@extends('layouts.frontend')

@section('title','Home Page')

@section('content')

<div class="listing-hero">
    <div>
        <div class="listing-h">All Products</div>
        <div style="font-size:13px;color:var(--charcoal-60);margin-top:4px;">
            {{ \App\Models\Product::where('status',1)->count() }} items available
        </div>
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

                <div class="sidebar-filter-item">
                    All
                    <span class="count">
                        {{ \App\Models\Product::where('status',1)->count() }}
                    </span>
                </div>

                @foreach($data['categories'] as $category)

                    <a href="{{ route('frontend.listing',$category->slug) }}"
                       class="sidebar-filter-item">

                        {{ $category->title }}

                        <span class="count">
                            {{ $category->products()->where('status',1)->count() }}
                        </span>

                    </a>

                @endforeach

            </div>
        </div>

        <!-- PRICE -->
        <div>
            <div class="sidebar-group-title">Price</div>

            <div class="price-range">
                <input class="price-input" type="text" placeholder="$0">
                <input class="price-input" type="text" placeholder="$500">
            </div>
        </div>

    </div>

    <!-- MAIN -->
    <div class="listing-main">

        <div class="active-filters">
            <div class="active-filter-tag">Home <span>×</span></div>
            <div class="clear-all">Clear all</div>
        </div>

        <div class="listing-products">

            {{-- SAFE CHECK (IMPORTANT FIX) --}}
            @if(!empty($data['products']) && count($data['products']))

                @foreach($data['products'] as $product)

                    <div class="product-card">

                        <a href="{{ route('frontend.details',$product->slug) }}">

                            <div class="product-thumb">

                                @if($product->images->first())
                                    <img src="{{ asset('uploads/products/' . $product->images->first()->image_name) }}">
                                @else
                                    <div style="font-size:40px;">📦</div>
                                @endif

                                <div class="product-wish">♡</div>

                            </div>

                            <div class="product-name">
                                {{ $product->title }}
                            </div>

                            <div class="product-cat">
                                {{ $product->category->title }}
                            </div>

                            <div class="product-footer">
                                <span class="product-price">
                                    Rs. {{ $product->price }}
                                </span>

                                <button class="add-to-cart">+ Add</button>
                            </div>

                        </a>

                    </div>

                @endforeach

            @else

                {{-- FALLBACK (IMPORTANT) --}}
                @foreach(\App\Models\Product::where('status',1)->latest()->limit(12)->get() as $product)

                    <div class="product-card">

                        <a href="{{ route('frontend.details',$product->slug) }}">

                            <div class="product-thumb">

                                @if($product->images->first())
                                    <img src="{{ asset('uploads/products/' . $product->images->first()->image_name) }}">
                                @else
                                    <div style="font-size:40px;">📦</div>
                                @endif

                                <div class="product-wish">♡</div>

                            </div>

                            <div class="product-name">
                                {{ $product->title }}
                            </div>

                            <div class="product-cat">
                                {{ $product->category->title }}
                            </div>

                            <div class="product-footer">
                                <span class="product-price">
                                    Rs. {{ $product->price }}
                                </span>

                                <button class="add-to-cart">+ Add</button>
                            </div>

                        </a>

                    </div>

                @endforeach

            @endif

        </div>

    </div>

</div>

@endsection