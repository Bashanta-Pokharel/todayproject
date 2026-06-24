@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sales</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($data['totalSales'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Orders</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['totalOrders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Orders</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['pendingOrders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Low Stock</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['lowStockProducts'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($data['recentOrders'] as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>Rs. {{ number_format($order->grand_total, 2) }}</td>
                                <td>{{ ucfirst($order->order_status) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</td>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No orders yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Store Health</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">Customers: <strong>{{ $data['totalCustomers'] }}</strong></p>
                    <p class="mb-2">Products: <strong>{{ $data['totalProducts'] }}</strong></p>
                    <p class="mb-0">Active Categories: <strong>{{ $data['activeCategories'] }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
