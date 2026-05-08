@extends('layouts.frontend')
@section('title','Home Page')
@section('content')
    <div class="listing-hero">
        <div>
            <div class="listing-h">{{$data['category']->title}}</div>
            <div style="font-size:13px;color:var(--charcoal-60);margin-top:4px;">{{$data['category']->products()->where('status',1)->count()}} items</div>
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
                    <div class="sidebar-filter-item">All <span class="count">{{\App\Models\Product::where('status',1)->count()}}</span></div>
                    @foreach($data['categories'] as $category)
                        <div class="sidebar-filter-item @if($category->slug == $data['category']->slug) active @endif">{{$category->title}} <span class="count">24</span></div>
                    @endforeach
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
                @foreach($data['products'] as $product)
                    <div class="product-card">
                        <a href="{{route('frontend.details',$product->slug)}}">
                            <div class="product-thumb">
                                <div class="product-badge">New</div>
                                <div class="product-wish">♡</div>
                                <img src="{{asset('uploads/products/' . $product->images()->first()->image_name )}}" alt="">
                            </div>
                            <div class="product-name">{{$product->title}}</div>
                            <div class="product-cat">{{$product->category->title}}</div>
                            <div class="product-footer">
                                <div><span class="product-price">Rs.{{$product->price}}</span></div><button class="add-to-cart">+ Add</button>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            {{$data['category']->products()->paginate(2)}}
        </div>
    </div>
@endsection

