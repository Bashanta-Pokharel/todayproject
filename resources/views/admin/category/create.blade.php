@extends('layouts.admin')

@section('title','Category Create | Category Management')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">
        Category Management
        <a class="btn btn-primary" href="{{ route('admin.category.index') }}">
            List
        </a>
    </h1>

    <!-- Card -->
    <div class="card shadow mb-4">

        <!-- Header -->
        <a href="#collapseCategoryCard"
           class="d-block card-header py-3"
           data-toggle="collapse"
           role="button">

            <h6 class="m-0 font-weight-bold text-primary">
                Create Category
            </h6>

        </a>

        <!-- Body -->
        <div class="collapse show" id="collapseCategoryCard">

            <div class="card-body">

                {{-- Flash Message --}}
                @include('admin.includes.flash_message')

                <form action="{{ route('admin.category.store') }}" method="POST">
                    @csrf

                    <!-- Title -->
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">

                        <small class="text-danger">
                            @error('title') {{ $message }} @enderror
                        </small>
                    </div>

                    <!-- Slug -->
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">

                        <small class="text-danger">
                            @error('slug') {{ $message }} @enderror
                        </small>
                    </div>

                    <!-- Rank -->
                    <div class="form-group">
                        <label>Rank</label>
                        <input type="number" name="rank" class="form-control" value="{{ old('rank') }}">

                        <small class="text-danger">
                            @error('rank') {{ $message }} @enderror
                        </small>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label>Status</label><br>

                        <input type="radio" name="status" value="1" id="publish" @checked(old('status', '1') === '1')>
                        <label for="publish">Publish</label>

                        <input type="radio" name="status" value="0" id="unpublish" @checked(old('status') === '0')>
                        <label for="unpublish">Unpublish</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary">
                        Save Category
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
@endsection
