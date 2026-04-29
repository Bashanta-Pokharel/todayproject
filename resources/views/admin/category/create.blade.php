
@extends('layouts.admin')
@section('title','Category Create | Category Management')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Category Management
        <a class="btn btn-primary" href="{{ route('admin.category.index') }}">List</a>
    </h1>
     <!-- Collapsable Card Example -->
     <div class="card shadow mb-4">
        <!-- Card Header - Accordion -->
        <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
            role="button" aria-expanded="true" aria-controls="collapseCardExample">
            <h6 class="m-0 font-weight-bold text-primary">Create Category</h6>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="collapseCardExample">
            <div class="card-body">
                @include('admin.includes.flash_message')
                <form action="{{route('admin.category.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                      <label for="title">Title</label>
                      <input type="text" name="title" class="form-control" id="title" aria-describedby="titleHelp">
                      <small id="titleHelp" class="form-text text-danger">@error('title') {{$message}}@enderror</small>
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" class="form-control" id="slug" aria-describedby="slugHelp">
                        <small id="slugHelp" class="form-text text-danger">@error('slug') {{$message}}@enderror</small>
                    </div>
                    <div class="form-group">
                        <label for="rank">Rank</label>
                        <input type="number" name="rank" class="form-control" id="rank" aria-describedby="rankHelp">
                        <small id="slugHelp" class="form-text text-danger">@error('rank') {{$message}}@enderror</small>
                    </div>
                    <div class="form-group form-radio">
                        <label for="rank">Status</label>
                      <input type="radio" name="status" value="1" class="form-radio-input" id="exampleRadio1">
                      <label class="form-check-label" for="exampleCheck1">Publish</label>
                      <input type="radio" name="status" value="0" class="form-radio-input" id="exampleRadio2" checked>
                      <label class="form-check-label" for="exampleCheck2">Un-Publish</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                  </form>
            </div>
        </div>
    </div>
</div>
@endsection
