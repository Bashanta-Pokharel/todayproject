<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $data['records'] = Order::with('customer')
            ->when($request->filled('status'), fn ($query) => $query->where('order_status', $request->query('status')))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->query('payment_status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.order.index', compact('data'));
    }

    public function show(Order $order): View
    {
        $order->load(['items.product.images', 'transactions', 'customer']);

        return view('admin.order.show', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => ['required', 'in:pending,confirmed,processing,shipped,delivered,cancelled'],
            'payment_status' => ['required', 'in:pending,paid,failed,refunded,pending_collection'],
        ]);

        $timestamps = [];

        if ($validated['order_status'] === 'confirmed' && ! $order->confirmed_at) {
            $timestamps['confirmed_at'] = now();
        }

        if ($validated['order_status'] === 'shipped' && ! $order->shipped_at) {
            $timestamps['shipped_at'] = now();
        }

        if ($validated['order_status'] === 'delivered' && ! $order->delivered_at) {
            $timestamps['delivered_at'] = now();
        }

        if ($validated['payment_status'] === 'paid' && ! $order->paid_at) {
            $timestamps['paid_at'] = now();
        }

        $order->update($validated + $timestamps);

        return back()->with('success', 'Order updated successfully.');
    }
}
