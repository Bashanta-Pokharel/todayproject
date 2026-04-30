@extends('layouts.admin')

@section('title','List | Attribute Management')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Attribute Management</h1>

    <div class="card shadow mb-4">

        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Attribute List</h6>
        </div>

        <div class="card-body">
            @include('admin.includes.flash_message')

            {{-- Create Button --}}
            <a href="{{ route('admin.attribute.create') }}" class="btn btn-primary mb-3">
                Create Attribute
            </a>
            <a href="{{ route('admin.attribute.trashed') }}" class="btn btn-danger mb-3">
                View Trashed Attributes
            </a>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data['records'] as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td>{{ $record->title }}</td>

                            <td>
                                @if($record->status == 1)
                                    <span class="text-success">Published</span>
                                @else
                                    <span class="text-danger">Un-Published</span>
                                @endif
                            </td>

                            <td>
                                {{ \App\Models\User::find($record->created_by)->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ \App\Models\User::find($record->updated_by)->name ?? 'N/A' }}
                            </td>

                            <td>
                                <a href="{{ route('admin.attribute.show', $record->id) }}" class="btn btn-info btn-sm">View</a>

                                <a href="{{ route('admin.attribute.edit', $record->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('admin.attribute.destroy', $record->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
                

            </table>
            <a href="{{ route('admin.attribute.create') }}" class="btn btn-primary mb-3">
                Create Attribute
            </a>

        </div>
    </div>
</div>
@endsection