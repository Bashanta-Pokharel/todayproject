@extends('layouts.admin')
@section('title','List | Category Management')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Category Management</h1>
                      <a href="{{ route('admin.category.create') }}" class="btn btn-success">Create Category</a>
                      <!-- trash link -->
                       <a href="{{ route('admin.category.trashed') }}" class="btn btn-danger">Trashed Items</a>

     <!-- Collapsable Card Example -->
     <div class="card shadow mb-4">
        <!-- Card Header - Accordion -->
        <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
            role="button" aria-expanded="true" aria-controls="collapseCardExample">
            <h6 class="m-0 font-weight-bold text-primary">Category List</h6>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="collapseCardExample">
            <div class="card-body">
                @include('admin.includes.flash_message')
                <table class="table table-bordered">
                    <tr>
                        <th>SN</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Rank</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    @foreach($data['records'] as $record)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$record->title}}</td>
                        <td>{{$record->slug}}</td>
                        <td>{{$record->rank}}</td>
                        <td>
                            @if($record->status == 1)
                                <span class="text-success">Published</span>
                            @else
                                <span class="text-danger">Un-Published</span>
                            @endif
                        </td>
                        <td>
                        @if($record->created_by)
                        {{App\Models\User::find($record->created_by)->name}}
                        @endif
                        </td>
                        <td>{{$record->created_at}}</td>
                        <td>
                          <!-- view detail with show.blade.php -->
                          <a href="{{ route('admin.category.show', $record->id) }}" class="btn btn-info">View details</a>
                          <form action="{{ route('admin.category.destroy', $record->id) }}" method="POST" style="display:inline-block">
                            <!-- edit form button -->
                            <a href="{{ route('admin.category.edit', $record->id) }}" class="btn btn-warning">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </table>  
                  <!-create linker-!>
                  <a href="{{ route('admin.category.create') }}" class="btn btn-primary">Create Category</a>
            </div>
        </div>
    </div>
</div>
@endsection



