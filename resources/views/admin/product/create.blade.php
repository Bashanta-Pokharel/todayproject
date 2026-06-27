        @extends('layouts.admin')

        @section('title','Create Product')

        @section('content')
            <div class="container-fluid">

                <!-- ================= HEADER ================= -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800">Product Management</h1>

                    <a href="{{ route('admin.product.index') }}" class="btn btn-secondary btn-sm">
                        Product List
                    </a>
                </div>

                <div class="card shadow mb-4">
                    <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h6 class="m-0 font-weight-bold text-primary">Create Product</h6>
                    </a>
                    <div class="card-body">

                            <!-- ================= NAV TABS ================= -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#product">Basic Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#images">Image Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#attributes">Attributes</a>
                                </li>
                            </ul>
                            <form action="{{route('admin.product.store')}}" enctype="multipart/form-data" method="post">
                                @csrf
                            <div class="tab-content pt-3">

                                <!-- ================= PRODUCT TAB ================= -->
                                <div class="tab-pane fade show active" id="product">

                                    <!-- CATEGORY DROPDOWN -->
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select name="category_id" class="form-control">
                                            <option value="">-- Select Category --</option>
                                            @foreach($data['categories'] as $category)
                                                <option value="{{$category->id}}" @selected(old('category_id') == $category->id)>{{$category->title}}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text"  placeholder="Enter title" name="title" class="form-control" value="{{ old('title') }}">
                                        @error('title')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Slug</label>
                                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                                        @error('slug')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="number" min="0" name="quantity" class="form-control" value="{{ old('quantity') }}">
                                        @error('quantity')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price') }}">
                                        @error('price')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Discount</label>
                                        <input type="number" step="0.01" min="0" name="discount" class="form-control" value="{{ old('discount', 0) }}">
                                        @error('discount')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <!-- DESCRIPTION WITH EDITOR ID -->
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" id="editor" class="form-control">{{ old('description') }}</textarea>
                                        @error('description')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label><br>
                                        <input type="radio" name="status" value="1" @checked(old('status', '1') === '1')> Publish
                                        <input type="radio" name="status" value="0" @checked(old('status') === '0')> Un-Publish
                                    </div>

                                </div>

                                <!-- ================= IMAGES TAB ================= -->
                                <div class="tab-pane fade" id="images">

                                    <table class="table table-bordered" id="image-table">

                                        <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>
                                                Action
                                                <button type="button" class="btn btn-success btn-sm add-image float-right">Add</button>
                                            </th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr class="image-row">
                                            <td><input type="file" name="image_name[]" class="form-control"></td>
                                            <td><input type="text" name="image_title[]" class="form-control"></td>
                                            <td>
                                                <input type="radio" name="image_status[0]" value="1"> Active
                                                <input type="radio" name="image_status[0]" value="0"> Inactive
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-image">Remove</button>
                                            </td>
                                        </tr>
                                        </tbody>

                                    </table>

                                </div>

                                <!-- ================= ATTRIBUTES TAB ================= -->
                                <div class="tab-pane fade" id="attributes">

                                    <table class="table table-bordered" id="attribute-table">

                                        <thead>
                                        <tr>
                                            <th>Attribute</th>
                                            <th>Value</th>
                                            <th>Status</th>
                                            <th>
                                                Action
                                                <button type="button" class="btn btn-success btn-sm add-attribute float-right">Add</button>
                                            </th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr class="attribute-row">

                                            <td>
                                                <select name="attribute_id[]" class="form-control">
                                                    <option value="">-- Select Attribute --</option>
                                                    @foreach($data['attributes'] as $attribute)
                                                        <option value="{{$attribute->id}}">{{$attribute->title}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="values[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="radio" name="attr_status[0]" value="1"> Active
                                                <input type="radio" name="attr_status[0]" value="0"> Inactive
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-attribute">Remove</button>
                                            </td>

                                        </tr>
                                        </tbody>

                                    </table>

                                </div>

                            </div>

                            <button type="submit" class="btn btn-success mt-3">
                                Create Product
                            </button>
                        </form>

                    </div>
                </div>

            </div>

            <!-- ================= JAVASCRIPT ================= -->
            <script>
                let imgIndex = 1;
                let attrIndex = 1;

                document.addEventListener('click', function (e) {

                    if (e.target.classList.contains('add-image')) {

                        let row = document.querySelector('.image-row').cloneNode(true);

                        row.querySelectorAll('input').forEach(input => {
                            if (input.type !== 'radio') input.value = '';
                        });

                        row.querySelectorAll('input[type="radio"]').forEach(radio => {
                            radio.name = 'image_status[' + imgIndex + ']';
                            radio.checked = false;
                        });

                        document.querySelector('#image-table tbody').appendChild(row);
                        imgIndex++;
                    }

                    if (e.target.classList.contains('remove-image')) {
                        let rows = document.querySelectorAll('#image-table tbody tr');
                        if (rows.length > 1) e.target.closest('tr').remove();
                    }

                    if (e.target.classList.contains('add-attribute')) {

                        let row = document.querySelector('.attribute-row').cloneNode(true);

                        row.querySelectorAll('input, select').forEach(el => {
                            if (el.type !== 'radio') el.value = '';
                        });

                        row.querySelectorAll('input[type="radio"]').forEach(radio => {
                            radio.name = 'attr_status[' + attrIndex + ']';
                            radio.checked = false;
                        });

                        document.querySelector('#attribute-table tbody').appendChild(row);
                        attrIndex++;
                    }

                    if (e.target.classList.contains('remove-attribute')) {
                        let rows = document.querySelectorAll('#attribute-table tbody tr');
                        if (rows.length > 1) e.target.closest('tr').remove();
                    }

                });
            </script>

            <!-- ================= CKEDITOR ================= -->
            <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
            <script>
                CKEDITOR.replace('editor');
            </script>

        @endsection


