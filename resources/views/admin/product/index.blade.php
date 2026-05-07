    @extends('layouts.admin')

    @section('title','List | Product Management')

    @section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Product Management</h1>

        <a href="{{ route('admin.product.create') }}" class="btn btn-success">Create Product</a>

        <!-- Collapsable Card -->
        <div class="card shadow mb-4 mt-3">

            <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
            role="button" aria-expanded="true" aria-controls="collapseCardExample">
                <h6 class="m-0 font-weight-bold text-primary">Product List</h6>
            </a>

            <div class="collapse show" id="collapseCardExample">
                <div class="card-body">

                    @include('admin.includes.flash_message')

                    <table class="table table-bordered">
                        <tr>
                            <th>SN</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>

                        @foreach($data['records'] as $record)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>

                            <!-- Category -->
                            <td>
                                {{ $record->category->title ?? 'N/A' }}
                            </td>

                            <td>{{ $record->title }}</td>
                            <td>{{ $record->slug }}</td>
                            <td>{{ $record->price }}</td>
                            <td>{{ $record->quantity }}</td>

                            <!-- Status -->
                            <td>
                                @if($record->status == 1)
                                    <span class="text-success">Published</span>
                                @else
                                    <span class="text-danger">Un-Published</span>
                                @endif
                            </td>

                            <!-- Created By -->
                            <td>
                                @if($record->created_by)
                                    {{ App\Models\User::find($record->created_by)->name }}
                                @endif
                            </td>

                            <td>{{ $record->created_at }}</td>

                            <!-- Actions -->
                            <td>
                                <a href="{{ route('admin.product.show', $record->id) }}" class="btn btn-info btn-sm">View</a>

                                <a href="{{ route('admin.product.edit', $record->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('admin.product.destroy', $record->id) }}"
                                    method="POST"
                                    style="display:inline-block">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                    </table>

                    <!-- Create button bottom -->
                    <a href="{{ route('admin.product.create') }}" class="btn btn-primary">
                        Create Product
                    </a>

                </div>
            </div>
        </div>
    </div>
    @endsection