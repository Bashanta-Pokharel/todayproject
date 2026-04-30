@extends('layouts.admin')

@section('title','Multi Tab Form')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Multi Step Product Form</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- back to list dashboard -->
            <a href="{{ route('dashboard') }}" class="btn btn-success btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <!-- attribute and category add -->
            <a href="{{ route('admin.attribute.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Attribute
            </a>
            <a href="{{ route('admin.category.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Category
            </a>
            <!-- view products -->
            <a href="{{ route('dashboard') }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> View Products
            </a>
            <br><br>

            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- 🔵 NAV TABS -->
                <ul class="nav nav-tabs" role="tablist">

                    <!-- TAB 1: PRODUCT -->
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#product">
                            Product
                        </a>
                    </li>

                    <!-- TAB 2: IMAGES -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#images">
                            Product Images
                        </a>
                    </li>

                    <!-- TAB 3: ATTRIBUTES -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#attributes">
                            Product Attributes
                        </a>
                    </li>

                </ul>

                <!-- 🔵 TAB CONTENT -->
                <div class="tab-content pt-3">

                    <!-- ================= TAB 1: PRODUCT ================= -->
                    <div class="tab-pane fade show active" id="product">

                        <div class="form-group">
                            <label>Category ID</label>
                            <input type="text" name="category_id" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" name="slug" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Discount</label>
                            <input type="text" name="discount" value="0" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Status</label><br>
                            <input type="radio" name="status" value="1"> Active
                            <input type="radio" name="status" value="0"> Inactive
                        </div>

                    </div>

                    <!-- ================= TAB 2: PRODUCT IMAGES ================= -->
                    <div class="tab-pane fade" id="images">

                        <div class="form-group">
                            <label>Product Image</label>
                            <input type="file" name="image_name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Image Title</label>
                            <input type="text" name="image_title" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Status</label><br>
                            <input type="radio" name="image_status" value="1"> Active
                            <input type="radio" name="image_status" value="0"> Inactive
                        </div>

                    </div>

                    <!-- ================= TAB 3: PRODUCT ATTRIBUTES ================= -->
                    <div class="tab-pane fade" id="attributes">

                        <div class="form-group">
                            <label>Attribute ID</label>
                            <input type="text" name="attribute_id" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Values</label>
                            <input type="text" name="values" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Status</label><br>
                            <input type="radio" name="attr_status" value="1"> Active
                            <input type="radio" name="attr_status" value="0"> Inactive
                        </div>

                    </div>

                </div>

                <button type="submit" class="btn btn-success mt-3">
                    Submit All Data
                </button>

            </form>
            <br>
             <a href="{{ route('dashboard') }}" class="btn btn-success btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <!-- attribute and category add -->
            <a href="{{ route('admin.attribute.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Attribute
            </a>
            <a href="{{ route('admin.category.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Category
            </a>
            <!-- view products -->
            <a href="{{ route('dashboard') }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> View Products
            </a>

            

        </div>
        
    </div>

</div>
@endsection