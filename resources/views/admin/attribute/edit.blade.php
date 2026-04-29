@extends('layouts.admin')
@section('title','edit | Category edit page')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Category Management</h1>
     <!-- Collapsable Card Example -->
     <div class="card shadow mb-4">
        <!-- Card Header - Accordion -->
        <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
            role="button" aria-expanded="true" aria-controls="collapseCardExample">
            <h6 class="m-0 font-weight-bold text-primary">Edit Category</h6>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="collapseCardExample">
            <div class="card-body">
                <form>
                    <div class="form-group">
                      <label for="title">Title</label>
                      <input type="text" class="form-control" id="title" aria-describedby="titleHelp" value="Mobile Phone">
                      <small id="titleHelp" class="form-text text-muted">Please Input Unique Title</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection