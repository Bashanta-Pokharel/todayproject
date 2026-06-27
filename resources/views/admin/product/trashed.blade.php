@extends('layouts.admin')

@section('title', 'Trash | Product Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Trashed Products</h1>
        <div class="mt-3 mt-sm-0">
            <a class="btn btn-success btn-sm" href="{{ route('admin.product.create') }}">
                <i class="fas fa-plus mr-1"></i> Create Product
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.product.index') }}">
                <i class="fas fa-list mr-1"></i> Product List
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Deleted Products</h6>
        </div>

        <div class="card-body">
            @include('admin.includes.flash_message')

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Deleted At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['records'] as $record)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $record->category->title ?? 'N/A' }}</td>
                                <td>{{ $record->title }}</td>
                                <td>Rs. {{ number_format((float) $record->price, 2) }}</td>
                                <td>{{ $record->quantity }}</td>
                                <td>
                                    @if($record->status)
                                        <span class="badge badge-success">Published</span>
                                    @else
                                        <span class="badge badge-secondary">Unpublished</span>
                                    @endif
                                </td>
                                <td>{{ $record->deleted_at?->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.product.restore', $record->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Restore</button>
                                    </form>

                                    <form action="{{ route('admin.product.force-delete', $record->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete Forever</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No trashed products.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
