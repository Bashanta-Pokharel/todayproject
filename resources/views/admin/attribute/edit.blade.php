@extends('layouts.admin')
@section('title','Edit | Attribute Management')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">
        Attribute Management
        <a class="btn btn-primary" href="{{ route('admin.attribute.index') }}">List</a>
    </h1>

    <div class="card shadow mb-4">
        <a href="#collapseCardExample" class="d-block card-header py-3"
           data-toggle="collapse" role="button" aria-expanded="true">
            <h6 class="m-0 font-weight-bold text-primary">Edit Attribute</h6>
        </a>

        <div class="collapse show" id="collapseCardExample">
            <div class="card-body">

                {{-- ✅ FLASH MESSAGE --}}
                @include('admin.includes.flash_message')

                <form action="{{ route('admin.attribute.update', $record->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               id="title"
                               value="{{ old('title', $record->title) }}">

                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
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
                        Update Attribute
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection