@extends('layouts.admin')

@section('title', 'Order '.$order->order_number)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Order {{ $order->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @include('admin.includes.flash_message')

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Items</h6></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    {{ $item->product_title }}<br>
                                    <small>{{ collect($item->attributes)->map(fn ($value, $key) => $key.': '.$value)->implode(', ') }}</small>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                                <td>Rs. {{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Update Status</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label>Order Status</label>
                            <select name="order_status" class="form-control">
                                @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($order->order_status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Payment Status</label>
                            <select name="payment_status" class="form-control">
                                @foreach(['pending','paid','failed','refunded','pending_collection'] as $status)
                                    <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary btn-block">Save</button>
                    </form>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Summary</h6></div>
                <div class="card-body">
                    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer_phone ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                    <hr>
                    <p class="d-flex justify-content-between"><span>Subtotal</span><strong>Rs. {{ number_format($order->subtotal, 2) }}</strong></p>
                    <p class="d-flex justify-content-between"><span>Discount</span><strong>Rs. {{ number_format($order->discount_total, 2) }}</strong></p>
                    <p class="d-flex justify-content-between"><span>Shipping</span><strong>Rs. {{ number_format($order->shipping_total, 2) }}</strong></p>
                    <p class="d-flex justify-content-between h5"><span>Total</span><strong>Rs. {{ number_format($order->grand_total, 2) }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
