@extends('layouts/admin')

@section('title','Admin Dashboard')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <div class="row">

        <!-- Category -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <h5>Category</h5>
                    <a href="{{ route('admin.category.create') }}" class="btn btn-primary btn-sm">Add Category</a>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-success btn-sm">View Categories</a>
                </div>
            </div>
        </div>

        <!-- Attribute -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <h5>Attribute</h5>
                    <a href="{{ route('admin.attribute.create') }}" class="btn btn-primary btn-sm">Add Attribute</a>
                    <a href="{{ route('admin.attribute.index') }}" class="btn btn-success btn-sm">View Attributes</a>
                </div>
            </div>
        </div>

        <!-- Product -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <h5>Product</h5>
                    <a href="{{ route('trytab') }}" class="btn btn-primary btn-sm">Add Product</a>
                    <a href="#" class="btn btn-success btn-sm">View Products</a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection