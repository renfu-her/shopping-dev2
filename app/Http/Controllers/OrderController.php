<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('member_id', auth()->guard('member')->id())
            ->with(['items.product.primaryImage'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Check if the order belongs to the authenticated member
        if ($order->member_id !== auth()->guard('member')->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['items.product.primaryImage', 'member']);

        return view('pages.orders.show', compact('order'));
    }
}
