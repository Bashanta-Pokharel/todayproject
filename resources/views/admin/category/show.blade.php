@extends('layouts.admin')
@section('title','Category Create | Category Management')
@section('content')
<!-- makinf a detail page -->
<div class="container-fluid">
    <a href="{{ route('admin.category.index') }}" class="btn btn-primary">Back to List</a>
                    <a href="{{ route('admin.category.edit', $record->id) }}" class="btn btn-info">Edit Category</a>
                    <a href="{{ route('admin.category.create') }}" class="btn btn-success">Create New Category</a> 
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Category Management</h1>
     <!-- Collapsable Card Example -->
     <div class="card shadow mb-4">
        <!-- Card Header - Accordion -->
        <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
            role="button" aria-expanded="true" aria-controls="collapseCardExample">
            <h6 class="m-0 font-weight-bold text-primary">Category Detail</h6>
        </a>
        
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="collapseCardExample">
            <div class="card-body">
                @include('admin.includes.flash_message')
                <table class="table table-bordered">
                    <tr>
                        <th>Title</th>
                        <td>{{$record->title}}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{$record->slug}}</td>
                    </tr>
                    <tr>
                        <th>Rank</th>
                        <td>{{$record->rank}}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($record->status == 1)
                                <span class="text-success">Published</span>
                            @else
                                <span class="text-danger">Un-Published</span>
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <th>Created Date</th>
                        <td>{{$record->created_at}}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{$record->updated_at}}</td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td>
                        @if($record->created_by)
                        {{App\Models\User::find($record->created_by)->name}}
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Updated By</th>
                        <td>
                        @if($record->updated_by)
                        {{App\Models\User::find($record->updated_by)->name}}
                        @endif
                        </td>

                </table>
                    <!-create linker-!>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-primary">Back to List</a>
                    <a href="{{ route('admin.category.edit', $record->id) }}" class="btn btn-info">Edit Category</a>
                    <a href="{{ route('admin.category.create') }}" class="btn btn-success">Create New Category</a>  
            </div>
        </div>
    </div>
    @endsection