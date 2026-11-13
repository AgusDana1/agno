<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function getCart(Request $request): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);
    }

    public function index(Request $request)
    {
        $cart = $this->getCart($request);

        $cart->load([
            'items.product',
        ]);

        return view('user.cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        if (! $product->is_active) {
            return back()->with(
                'error',
                'Produk tidak tersedia.'
            );
        }

        $cart = $this->getCart($request);

        DB::transaction(function () use ($cart, $product, $validated) {
            $item = $cart->items()->where('product_id', $product->id)->first();

            $newQuantity = ($item?->quantity ?? 0) + $validated['quantity'];

            if ($newQuantity > $product->stock) {
                abort(422, 'Jumlah produk melebihi stock.');
            }

            if ($item) {
                $item->update([
                    'quantity' => $newQuantity,
                    'price' => $product->price,
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'price' => $product->price,
                ]);
            }
        });

        return back()->with(
            'success',
            'Produk ditambahkan ke keranjang'
        );
    }

    public function update(Request $request, $item)
    {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $cart = $this->getCart($request);

        $cartItem = $cart->items()->with('product')->findOrFail($item);

        if (! $cartItem->product->is_active || $validated['quantity'] > $cartItem->product->stock) {
            return back()->with(
                'error',
                'Jumlah produk melebihi stock.'
            );
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
            'price' => $cartItem->product->price,
        ]);

        return back()->with('success', 'Keranjang berhasil diupdate');
    }

    public function remove(Request $request, $item)
    {
        $cart = $this->getCart($request);

        $cart->items()->findOrFail($item)->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }
}
