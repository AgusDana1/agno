<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with([
            'user',
            'payment',
        ])->whereIn('status', [
            'paid',
            'processing',
            'shipped',
        ])->latest()->paginate(15);

        return view('pegawai.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'address',
            'items.product',
            'payment',
        ]);

        return view('pegawai.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'processing',
                    'shipped',
                    'completed',
                ]),
            ],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbaharui');
    }
}
