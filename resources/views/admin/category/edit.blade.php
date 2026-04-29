@extends('layouts.admin')
@section('title','Category Edit | Category Management')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">
        Category Management
        <a class="btn btn-primary" href="{{ route('admin.category.index') }}">List</a>
    </h1>

    <div class="card shadow mb-4">
        <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
           role="button" aria-expanded="true">
            <h6 class="m-0 font-weight-bold text-primary">Edit Category</h6>
        </a>

        <div class="collapse show" id="collapseCardExample">
            <div class="card-body">
                @include('admin.includes.flash_message')

                <form action="{{ route('admin.category.update', $record->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" 
                               name="title" 
                               class="form-control" 
                               id="title"
                               value="{{ old('title', $record->title) }}">
                        <small class="form-text text-danger">
                            @error('title') {{ $message }} @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" 
                               name="slug" 
                               class="form-control" 
                               id="slug"
                               value="{{ old('slug', $record->slug) }}">
                        <small class="form-text text-danger">
                            @error('slug') {{ $message }} @enderror
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="rank">Rank</label>
                        <input type="number" 
                               name="rank" 
                               class="form-control" 
                               id="rank"
                               value="{{ old('rank', $record->rank) }}">
                        <small class="form-text text-danger">
                            @error('rank') {{ $message }} @enderror
                        </small>
                    </div>

                    <div class="form-group form-radio">
                        <label>Status</label><br>

                        <input type="radio" name="status" value="1"
                            {{ old('status', $record->status) == 1 ? 'checked' : '' }}>
                        <label>Publish</label>

                        <input type="radio" name="status" value="0"
                            {{ old('status', $record->status) == 0 ? 'checked' : '' }}>
                        <label>Un-Publish</label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Update Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection