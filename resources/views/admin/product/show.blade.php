@extends('layouts.admin')

@section('title','View Product')

@section('content')
<div class="container-fluid">

    <!-- ================= HEADER ================= -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Product Details</h1>

        <div>
            <a href="{{ route('admin.product.index') }}" class="btn btn-secondary btn-lg">Back</a>
            <a href="{{ route('admin.product.create') }}" class="btn btn-success btn-lg">Create</a>
        </div>
    </div>

    <!-- ================= BASIC INFO ================= -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Basic Information</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered">

                <tr>
                    <th>Title</th>
                    <td>{{ $record->title }}</td>
                </tr>

                <tr>
                    <th>Slug</th>
                    <td>{{ $record->slug }}</td>
                </tr>

                <tr>
                    <th>Category</th>
                    <td>{{ $record->category->title ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Quantity</th>
                    <td>{{ $record->quantity }}</td>
                </tr>

                <tr>
                    <th>Price</th>
                    <td>Rs. {{ $record->price }}</td>
                </tr>

                <tr>
                    <th>Discount</th>
                    <td>{{ $record->discount }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        @if($record->status)
                            <span class="badge badge-success">Published</span>
                        @else
                            <span class="badge badge-danger">Unpublished</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Description</th>
                    <td>{!! $record->description !!}</td>
                </tr>

            </table>
        </div>
    </div>

    <!-- ================= IMAGES ================= -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Product Images</h5>
        </div>

        <div class="card-body">

            @if($record->images->count())
                <div class="row">
                    @foreach($record->images as $image)
                        <div class="col-md-3 mb-3">
                            <div class="card position-relative">

                                <!-- DELETE IMAGE -->
                                <form action="{{ route('admin.product.image.delete', $image->id) }}"
                                      method="POST"
                                      style="position:absolute; top:5px; right:5px;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete image?')">
                                        ✕
                                    </button>
                                </form>

                                <img src="{{ asset('uploads/products/'.$image->image_name) }}"
                                     class="card-img-top"
                                     style="height:200px; object-fit:cover;">

                                <div class="card-body text-center">
                                    <h6>{{ $image->image_title }}</h6>

                                    @if($image->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No images available</p>
            @endif

            <!-- ================= ADD IMAGE (LATER) ================= -->
            <hr>

            <h5>Add Image</h5>

            <form action="{{ route('admin.product.image.add', $record->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-md-4">
                        <input type="file" name="image_name" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <input type="text" name="image_title" class="form-control" placeholder="Image title">
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-success w-100">Add</button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- ================= ATTRIBUTES ================= -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Attributes</h5>
        </div>

        <div class="card-body">

            @if($record->attributes->count())
                <table class="table table-bordered">

                    <thead>
                    <tr>
                        <th>Attribute</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($record->attributes as $attr)
                        <tr>
                            <td>{{ $attr->title }}</td>
                            <td>{{ $attr->pivot->values }}</td>
                            <td>
                                @if($attr->pivot->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <form action="{{ route('admin.product.attribute.delete', [$record->id, $attr->id]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete attribute?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            @else
                <p>No attributes available</p>
            @endif

            <!-- ================= ADD ATTRIBUTE ================= -->
            <hr>

            <h5>Add Attribute</h5>

            <form action="{{ route('admin.product.attribute.add', $record->id) }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-4">
                        <select name="attribute_id" class="form-control" required>
                            <option value="">Select Attribute</option>
                            @foreach(\App\Models\Attribute::all() as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <input type="text" name="value" class="form-control" placeholder="Value" required>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-success w-100">Add</button>
                    </div>

                </div>
            </form>

        </div>
    </div>

</div>
@endsection