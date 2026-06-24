@extends('layouts.frontend')

@section('title', $data['category']->title.' Products')

@section('content')
<div class="listing-hero">
    <div>
        <div class="listing-h">{{ $data['category']->title }}</div>
        <div class="section-sub">{{ $data['products']->total() }} item(s)</div>
    </div>
</div>

<div class="listing-layout">
    <aside class="listing-sidebar">
        <div>
            <div class="sidebar-group-title">Category</div>
            <div class="sidebar-filters">
                <a href="{{ route('frontend.index') }}" class="sidebar-filter-item">All <span class="count">{{ $data['categories']->sum('products_count') }}</span></a>
                @foreach($data['categories'] as $category)
                    <a href="{{ route('frontend.listing', $category->slug) }}" @class(['sidebar-filter-item', 'active' => $category->id === $data['category']->id])>
                        {{ $category->title }} <span class="count">{{ $category->products_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </aside>

    <section class="listing-main">
        <form class="catalog-toolbar compact" method="GET" action="{{ route('frontend.listing', $data['category']->slug) }}">
            <input type="search" name="q" value="{{ $data['filters']['q'] ?? '' }}" placeholder="Search in {{ $data['category']->title }}">
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

        <div class="listing-products">
            @forelse($data['products'] as $product)
                @include('frontend.partials.product-card', ['product' => $product])
            @empty
                <div class="empty-state">No products found in this category.</div>
            @endforelse
        </div>

        <div class="pagination-wrap">{{ $data['products']->links() }}</div>
    </section>
</div>
@endsection
