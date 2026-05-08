@extends('layouts.admin')

@section('title','Edit Product')

@section('content')
<div class="container-fluid">

    <h3 class="mb-4">Edit Product</h3>

    <div class="card">
        <div class="card-body">

            <!-- TABS -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#product">Basic</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#images">Images</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#attributes">Attributes</a>
                </li>
            </ul>

            <div class="tab-content pt-3">

                <!-- ================= BASIC ================= -->
                <div class="tab-pane fade show active" id="product">

                    <form action="{{ route('admin.product.update',$record->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- CATEGORY -->
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">-- Select Category --</option>

                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $record->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <!-- TITLE -->
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title"
                                   value="{{ $record->title }}"
                                   class="form-control">
                        </div>

                        <!-- SLUG -->
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" name="slug"
                                   value="{{ $record->slug }}"
                                   class="form-control">
                        </div>

                        <!-- QUANTITY -->
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity"
                                   value="{{ $record->quantity }}"
                                   class="form-control">
                        </div>

                        <!-- PRICE -->
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price"
                                   value="{{ $record->price }}"
                                   class="form-control">
                        </div>

                        <!-- DISCOUNT -->
                        <div class="form-group">
                            <label>Discount</label>
                            <input type="text" name="discount"
                                   value="{{ $record->discount }}"
                                   class="form-control">
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="editor" class="form-control">{{ $record->description }}</textarea>
                        </div>

                        <!-- STATUS -->
                        <div class="form-group">
                            <label>Status</label><br>

                            <input type="radio" name="status" value="1"
                                {{ $record->status == 1 ? 'checked' : '' }}>
                            Publish

                            <input type="radio" name="status" value="0"
                                {{ $record->status == 0 ? 'checked' : '' }}>
                            Un-Publish
                        </div>

                        <button class="btn btn-primary">Update Product</button>
                    </form>

                </div>

                <!-- ================= IMAGES ================= -->
                <div class="tab-pane fade" id="images">

                    <!-- EXISTING IMAGES -->
                    <div class="row">
                        @foreach($record->images as $image)
                        <div class="col-md-3 mb-3">
                            <div class="card position-relative">

                                <form action="{{ route('admin.product.image.delete',$image->id) }}"
                                      method="POST"
                                      style="position:absolute; top:5px; right:5px;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">✕</button>
                                </form>

                                <img src="{{ asset('uploads/products/'.$image->image_name) }}"
                                     style="height:150px; width:100%; object-fit:cover;">

                                <div class="p-2 text-center">
                                    {{ $image->image_title }}
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- ADD IMAGE -->
                    <form action="{{ route('admin.product.image.add',$record->id) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <input type="file" name="image_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="image_title" class="form-control" placeholder="Title">
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

                <!-- ================= ATTRIBUTES ================= -->
                <div class="tab-pane fade" id="attributes">

                    <table class="table table-bordered" id="attr-table">

                        <thead>
                        <tr>
                            <th>Attribute</th>
                            <th>Value</th>
                            <th>Status</th>
                            <th>
                                <button type="button" class="btn btn-success btn-sm add-row">+</button>
                            </th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($record->attributes as $attr)
                        <tr>

                            <td>
                                <select name="attribute_id[]" class="form-control">

                                    @foreach(\App\Models\Attribute::all() as $attribute)
                                        <option value="{{ $attribute->id }}"
                                            {{ $attribute->id == $attr->pivot->attribute_id ? 'selected' : '' }}>
                                            {{ $attribute->title }}
                                        </option>
                                    @endforeach

                                </select>
                            </td>

                            <td>
                                <input type="text" name="values[]"
                                       value="{{ $attr->pivot->values }}"
                                       class="form-control">
                            </td>

                            <td>
                                <select name="attr_status[]" class="form-control">
                                    <option value="1" {{ $attr->pivot->status ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$attr->pivot->status ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger remove-row">-</button>
                            </td>

                        </tr>
                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>

</div>

<!-- JS -->
<script>
document.addEventListener('click', function(e){

    if(e.target.classList.contains('add-row')){
        let row = document.querySelector('#attr-table tbody tr').cloneNode(true);

        row.querySelectorAll('input').forEach(i => i.value = '');

        document.querySelector('#attr-table tbody').appendChild(row);
    }

    if(e.target.classList.contains('remove-row')){
        let rows = document.querySelectorAll('#attr-table tbody tr');
        if(rows.length > 1){
            e.target.closest('tr').remove();
        }
    }

});
</script>
<!-- CKEDITOR -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace('editor');
</script>

@endsection