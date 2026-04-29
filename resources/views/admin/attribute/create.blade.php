@extends('layouts.admin')

@section('title','Attribute Create | Attribute Management')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">
        Attribute Management
        <a class="btn btn-primary" href="{{ route('admin.attribute.index') }}">
            List
        </a>
    </h1>

    <!-- Collapsable Card Example -->
    <div class="card shadow mb-4">

        <!-- Card Header -->
        <a href="#collapseAttributeCard"
           class="d-block card-header py-3"
           data-toggle="collapse"
           role="button"
           aria-expanded="true"
           aria-controls="collapseAttributeCard">

            <h6 class="m-0 font-weight-bold text-primary">
                Create Attribute
            </h6>

        </a>

        <!-- Card Content -->
        <div class="collapse show" id="collapseAttributeCard">

            <div class="card-body">

                {{-- Flash Message --}}
                @include('admin.includes.flash_message')

                <form action="{{ route('admin.attribute.store') }}" method="post">

                    @csrf

                    <!-- Title -->
                    <div class="form-group">
                        <label for="title">Title</label>

                        <input type="text"
                               name="title"
                               class="form-control"
                               id="title">

                        <small class="form-text text-danger">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </small>
                    </div>

                    <!-- Slug -->
                    

                    <!-- Rank -->

                    <!-- Status -->
                    <div class="form-group form-radio">

                        <label>Status</label><br>

                        <input type="radio"
                               name="status"
                               value="1"
                               class="form-radio-input"
                               id="statusPublish">

                        <label for="statusPublish">
                            Publish
                        </label>

                        <input type="radio"
                               name="status"
                               value="0"
                               class="form-radio-input"
                               id="statusUnpublish"
                               checked>

                        <label for="statusUnpublish">
                            Un-Publish
                        </label>

                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            class="btn btn-primary">

                        Save Attribute

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
@endsection