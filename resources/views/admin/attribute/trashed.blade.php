@extends('layouts.admin')
@section('title','List | Attribute Management')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">
        Attribute Management
        <a class="btn btn-success" href="{{ route('admin.attribute.create') }}">Create</a>
        <a class="btn btn-primary" href="{{ route('admin.attribute.index') }}">List</a>
    </h1>

    <!-- Card -->
    <div class="card shadow mb-4">

        <a href="#collapseCardExample" class="d-block card-header py-3"
           data-toggle="collapse" role="button" aria-expanded="true"
           aria-controls="collapseCardExample">

            <h6 class="m-0 font-weight-bold text-primary">
                Attribute Trashed Items
            </h6>
        </a>

        <div class="collapse show" id="collapseCardExample">
            <div class="card-body">

                @include('admin.includes.flash_message')

                <table class="table table-bordered">
                    <tr>
                        <th>SN</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>

                    @foreach($data['records'] as $record)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>

                        <td>{{ $record->title }}</td>

                        <td>
                            @if($record->status == 1)
                                <span class="text-success">Published</span>
                            @else
                                <span class="text-danger">Un-Published</span>
                            @endif
                        </td>

                        <td>
                            {{ $record->created_by ? App\Models\User::find($record->created_by)->name : '' }}
                        </td>

                        <td>{{ $record->created_at }}</td>

                        <td>

                            <!-- Restore -->
                            <form action="{{ route('admin.attribute.restore', $record->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-warning mt-2">
                                    Restore
                                </button>
                            </form>

                            <!-- Force Delete -->
                            <form action="{{ route('admin.attribute.force-delete', $record->id) }}"
                                  method="post"
                                  onsubmit="return confirm('Are you sure you want to permanently delete this attribute?')">

                                @method('delete')
                                @csrf

                                <button type="submit" class="btn btn-danger mt-2">
                                    Force Delete
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach

                </table>

            </div>
        </div>
    </div>
</div>
@endsection