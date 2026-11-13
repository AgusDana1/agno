<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with([
            'user',
            'payment',
        ]);

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where(
                    'order_number',
                    'like',
                    "%{$search}%"
                );

                $q->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                });
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'address',
            'items.product',
            'payment',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'paid',
                    'processing',
                    'shipped',
                    'completed',
                    'cancelled',
                ]),
            ],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbaharui');
    }
}
