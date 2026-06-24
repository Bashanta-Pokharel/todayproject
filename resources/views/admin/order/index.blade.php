@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Order Management</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
        </div>
        <div class="card-body">
            @include('admin.includes.flash_message')
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Order Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($data['records'] as $record)
                        <tr>
                            <td>{{ $record->order_number }}</td>
                            <td>
                                {{ $record->customer_name }}<br>
                                <small>{{ $record->customer_email }}</small>
                            </td>
                            <td>Rs. {{ number_format($record->grand_total, 2) }}</td>
                            <td>{{ ucfirst($record->order_status) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $record->payment_status)) }}</td>
                            <td>{{ $record->created_at->format('M d, Y') }}</td>
                            <td><a href="{{ route('admin.orders.show', $record) }}" class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No orders found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $data['records']->links() }}
        </div>
    </div>
</div>
@endsection
