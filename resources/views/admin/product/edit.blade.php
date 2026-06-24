@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Edit Product</h1>
        <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @include('admin.includes.flash_message')

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.product.update', $record->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">-- Select Category --</option>
                                @foreach($data['categories'] as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $record->category_id) == $category->id)>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" value="{{ old('title', $record->title) }}" class="form-control">
                            @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $record->slug) }}" class="form-control">
                            @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Quantity</label>
                                <input type="number" name="quantity" min="0" value="{{ old('quantity', $record->quantity) }}" class="form-control">
                                @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Price</label>
                                <input type="number" step="0.01" name="price" value="{{ old('price', $record->price) }}" class="form-control">
                                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Discount</label>
                                <input type="number" step="0.01" name="discount" value="{{ old('discount', $record->discount) }}" class="form-control">
                                @error('discount') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="5">{{ old('description', $record->description) }}</textarea>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Status</label><br>
                            <label><input type="radio" name="status" value="1" @checked(old('status', $record->status) == 1)> Publish</label>
                            <label class="ml-3"><input type="radio" name="status" value="0" @checked(old('status', $record->status) == 0)> Unpublish</label>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold">Attributes</h6>
                        <table class="table table-bordered" id="attr-table">
                            <thead>
                                <tr>
                                    <th>Attribute</th>
                                    <th>Values</th>
                                    <th>Status</th>
                                    <th><button type="button" class="btn btn-success btn-sm add-row">Add</button></th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($record->attributes as $attr)
                                <tr>
                                    <td>
                                        <select name="attribute_id[]" class="form-control">
                                            <option value="">-- Select --</option>
                                            @foreach($data['attributes'] as $attribute)
                                                <option value="{{ $attribute->id }}" @selected($attribute->id == $attr->id)>{{ $attribute->title }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="values[]" value="{{ $attr->pivot->values }}" class="form-control"></td>
                                    <td>
                                        <select name="attr_status[]" class="form-control">
                                            <option value="1" @selected($attr->pivot->status)>Active</option>
                                            <option value="0" @selected(! $attr->pivot->status)>Inactive</option>
                                        </select>
                                    </td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td>
                                        <select name="attribute_id[]" class="form-control">
                                            <option value="">-- Select --</option>
                                            @foreach($data['attributes'] as $attribute)
                                                <option value="{{ $attribute->id }}">{{ $attribute->title }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="values[]" class="form-control"></td>
                                    <td>
                                        <select name="attr_status[]" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <button class="btn btn-primary">Update Product</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Images</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($record->images as $image)
                            <div class="col-6 mb-3">
                                <div class="card">
                                    <img src="{{ asset('uploads/products/'.$image->image_name) }}" style="height:120px; width:100%; object-fit:cover;" alt="{{ $image->image_title }}">
                                    <div class="p-2">
                                        <small>{{ $image->image_title }}</small>
                                        <form action="{{ route('admin.product.image.delete', $image->id) }}" method="POST" class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm btn-block">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('admin.product.image.add', $record->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="image_title" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button class="btn btn-success btn-block">Add Image</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('click', function (event) {
    if (event.target.classList.contains('add-row')) {
        const firstRow = document.querySelector('#attr-table tbody tr');
        const row = firstRow.cloneNode(true);
        row.querySelectorAll('input').forEach((input) => input.value = '');
        row.querySelectorAll('select').forEach((select) => select.selectedIndex = 0);
        document.querySelector('#attr-table tbody').appendChild(row);
    }

    if (event.target.classList.contains('remove-row')) {
        const rows = document.querySelectorAll('#attr-table tbody tr');
        if (rows.length > 1) {
            event.target.closest('tr').remove();
        }
    }
});
</script>
@endsection
