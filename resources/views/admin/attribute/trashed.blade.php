@extends('layouts.admin')

@section('title', 'Trash | Attribute Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Trashed Attributes</h1>
        <div class="mt-3 mt-sm-0">
            <a class="btn btn-success btn-sm" href="{{ route('admin.attribute.create') }}">
                <i class="fas fa-plus mr-1"></i> Create Attribute
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.attribute.index') }}">
                <i class="fas fa-list mr-1"></i> Attribute List
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Deleted Attributes</h6>
        </div>

        <div class="card-body">
            @include('admin.includes.flash_message')

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Deleted At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['records'] as $record)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $record->title }}</td>
                                <td>
                                    @if($record->status)
                                        <span class="badge badge-success">Published</span>
                                    @else
                                        <span class="badge badge-secondary">Unpublished</span>
                                    @endif
                                </td>
                                <td>{{ $record->deleted_at?->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.attribute.restore', $record->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Restore</button>
                                    </form>

                                    <form action="{{ route('admin.attribute.force-delete', $record->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this attribute?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete Forever</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No trashed attributes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
