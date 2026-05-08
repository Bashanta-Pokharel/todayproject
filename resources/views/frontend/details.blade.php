@extends('layouts.frontend')

@section('title','Home Page')

@section('content')

<style>
    .detail-gallery{
        display:flex;
        flex-direction:column;
        gap:15px;
    }

    .main-product-image{
        width:100%;
        height:500px;
        object-fit:cover;
        border-radius:16px;
        background:#f5f5f5;
    }

    .gallery-thumbs{
        display:flex;
        gap:10px;
        overflow-x:auto;
    }

    .gallery-thumb-image{
        width:90px;
        height:90px;
        object-fit:cover;
        border-radius:10px;
        cursor:pointer;
        border:2px solid transparent;
        transition:0.3s;
    }

    .gallery-thumb-image.active{
        border-color:#000;
    }
</style>

<div class="breadcrumb">
    <a href="#">Home</a>
    <span>›</span>

    <a href="{{route('frontend.listing',$data['product']->category->slug)}}">
        {{$data['product']->category->title}}
    </a>

    <span>›</span>

    {{$data['product']->title}}
</div>

<div class="detail-layout">

    <!-- IMAGE SECTION -->
    <div class="detail-gallery">

        @php
            $firstImage = $data['product']->images->first();
        @endphp

        <!-- MAIN IMAGE -->
        @if($firstImage)
            <img
                id="mainImage"
                src="{{ asset('uploads/products/' . $firstImage->image_name) }}"
                class="main-product-image"
                alt=""
            >
        @endif

        <!-- ALL IMAGES -->
        <div class="gallery-thumbs">

            @foreach($data['product']->images as $key => $image)

                <img
                    src="{{ asset('uploads/products/' . $image->image_name) }}"
                    class="gallery-thumb-image {{ $key == 0 ? 'active' : '' }}"
                    onclick="changeImage(this)"
                    alt=""
                >

            @endforeach

        </div>

    </div>

    <!-- INFO SECTION -->
    <div class="detail-info-panel">

        <div>
            <div class="detail-eyebrow">
                New Arrival · {{$data['product']->category->title}}
            </div>

            <div class="detail-name">
                {{$data['product']->title}}
            </div>
        </div>

        <div class="detail-price-row">

            <strike>
                <span class="detail-price">
                    Rs.{{$data['product']->price}}
                </span>
            </strike>

            <span class="detail-price">
                Rs.{{$data['product']->price-$data['product']->discount}}
            </span>

        </div>

        <div class="detail-rating">
            <span class="stars">★★★★★</span>
            <span>4.9 · 84 reviews</span>
        </div>

        <div class="divider"></div>

        <div class="detail-desc-text">
            {!! $data['product']->description  !!}
        </div>

        <!-- ATTRIBUTES -->
        @foreach($data['product']->attributes as $attribute)

            <div>

                <div class="detail-section-label">
                    {{$attribute->title}}
                </div>

                <select class="form-control" name="attribute[{{$attribute->id}}]">

                    <option value="">
                        Select {{$attribute->title}}
                    </option>

                    @foreach(explode(',',$attribute->pivot->values) as $value)

                        <option value="{{$value}}">
                            {{$value}}
                        </option>

                    @endforeach

                </select>

            </div>

        @endforeach

        <!-- QUANTITY -->
        <div>
            <div class="detail-section-label">Quantity</div>

            <div class="qty-row">
                <button class="qty-btn">−</button>
                <div class="qty-val">1</div>
                <button class="qty-btn">+</button>
            </div>
        </div>

        <!-- ACTION -->
        <div class="action-row">
            <button class="btn-full">Add to cart</button>
            <button class="btn-wish-full">♡</button>
        </div>

        <div class="divider"></div>

    </div>

</div>

<!-- RELATED -->
<div class="related-section">

    <div class="section-header">
        <div class="section-title">You may also like</div>
        <a href="#" class="section-link">View all →</a>
    </div>

    <div class="product-grid">

        <div class="product-card">
            <div class="product-thumb">
                <div class="product-badge">New</div>👔
            </div>

            <div class="product-name">Linen Shirt</div>

            <div class="product-cat">Tops</div>

            <div class="product-footer">
                <span class="product-price">$89</span>

                <button class="add-to-cart">
                    + Add
                </button>
            </div>
        </div>

        <div class="product-card">
            <div class="product-thumb">🧶</div>

            <div class="product-name">Merino Cardigan</div>

            <div class="product-cat">Tops</div>

            <div class="product-footer">
                <span class="product-price">$145</span>

                <button class="add-to-cart">
                    + Add
                </button>
            </div>
        </div>

        <div class="product-card">
            <div class="product-thumb">
                <div class="product-badge sale">Sale</div>🧤
            </div>

            <div class="product-name">Trench Coat</div>

            <div class="product-cat">Outerwear</div>

            <div class="product-footer">
                <span class="product-price">$345</span>

                <button class="add-to-cart">
                    + Add
                </button>
            </div>
        </div>

        <div class="product-card">
            <div class="product-thumb">👘</div>

            <div class="product-name">Silk Blouse</div>

            <div class="product-cat">Tops</div>

            <div class="product-footer">
                <span class="product-price">$165</span>

                <button class="add-to-cart">
                    + Add
                </button>
            </div>
        </div>

    </div>

</div>

<script>

    function changeImage(element){

        document.getElementById('mainImage').src = element.src;

        let images = document.querySelectorAll('.gallery-thumb-image');

        images.forEach((img)=>{
            img.classList.remove('active');
        });

        element.classList.add('active');
    }

</script>

@endsection