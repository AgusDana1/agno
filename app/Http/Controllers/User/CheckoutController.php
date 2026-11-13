<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Keranjang masih kosong.');
        }

        $addresses = $request->user()->addresses()->latest()->get();

        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('user.checkout.index', compact('cart', 'addresses', 'subtotal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_id' => [
                'required',
                'integer',
                'exists:addresses,id',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $order = DB::transaction(function () use ($request, $validated) {
            $address = $request->user()->addresses()->findOrFail($validated['address_id']);

            $cart = $request->user()->cart()->with('items.product')->first();

            if (! $cart || $cart->items->isEmpty()) {
                abort(422, 'Keranjang kosong');
            }

            $subtotal = 0;

            foreach ($cart->items as $item) {
                $product = $item->product()->lockForUpdate()->first();

                if (! $product) {
                    abort(422, 'Produk tidak ditemukan!');
                }

                if (! $product->is_active) {
                    abort(422, "Produk {$product->name} sudah tidak tersedia");
                }

                if ($product->stock < $item->quantity) {
                    abort(422, "Stock {$product->name} tidak mencukupi");
                }

                $subtotal += $product->price * $item->quantity;
            }

            $shippingCost = 0;
            $totalAmount = $subtotal + $shippingCost;

            $order = Order::create([
                'user_id' => $request->user()->id,
                'address_id' => $address->id,
                'order_number' => 'AGNO-' . strtoupper(Str::random(12)),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $product = $item->product()->lockForUpdate()->first();
                $subtotalItem = $product->price * $item->quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotalItem,
                ]);

                $product->decrement('stock', $item->quantity);
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => 'qris',
                'amount' => $totalAmount,
                'status' => 'pending',
            ]);

            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('user.orders.show', $order)->with('success', 'Pesanan berhasil dibuat.');
    }
}
