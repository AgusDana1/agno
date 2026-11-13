@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Keranjang Belanja</h1>
        <p class="text-sm text-gray-500 mt-1">Periksa item belanjaan Anda sebelum melanjutkan ke proses checkout</p>
    </div>

    @if($cart && $cart->items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Items List -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart->items as $item)
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center gap-5">
                        <!-- Product Image -->
                        <div class="w-24 h-24 rounded-xl bg-gray-50 overflow-hidden shrink-0">
                            <img 
                                src="{{ $item->product->primary_image_url }}" 
                                alt="{{ $item->product->name }}" 
                                class="w-full h-full object-cover"
                                onerror="this.src='https://placehold.co/200x200/e2e8f0/64748b?text={{ urlencode($item->product->name) }}'"
                            >
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 min-w-0 text-center sm:text-left">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="font-bold text-gray-800 hover:text-indigo-600 transition-colors line-clamp-1">
                                {{ $item->product->name }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">Kategori: {{ $item->product->category?->name ?? '-' }}</p>
                            <p class="text-sm font-bold text-indigo-600 mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>

                        <!-- Quantity Update Form -->
                        <form action="{{ route('user.cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center border border-gray-200 rounded-xl bg-gray-50">
                                <input 
                                    type="number" 
                                    name="quantity" 
                                    value="{{ $item->quantity }}" 
                                    min="1" 
                                    max="{{ $item->product->stock }}"
                                    class="w-16 text-center bg-transparent border-0 font-bold text-gray-800 text-sm focus:outline-none py-1.5"
                                >
                            </div>
                            <button type="submit" class="p-2 rounded-xl text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Perbarui Jumlah">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </button>
                        </form>

                        <!-- Subtotal & Delete -->
                        <div class="flex items-center justify-between sm:flex-col sm:items-end gap-3 w-full sm:w-auto">
                            <p class="text-sm font-black text-gray-900">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </p>
                            <form action="{{ route('user.cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary Card -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm sticky top-24 space-y-4">
                    <h3 class="font-bold text-gray-900 text-base">Ringkasan Belanja</h3>

                    @php
                        $totalPrice = $cart->items->sum(fn($i) => $i->price * $i->quantity);
                    @endphp

                    <div class="space-y-2 text-sm text-gray-600 border-b border-gray-100 pb-4">
                        <div class="flex justify-between">
                            <span>Total Item</span>
                            <span class="font-semibold text-gray-800">{{ $cart->items->sum('quantity') }} barang</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Pengiriman</span>
                            <span class="text-emerald-600 font-semibold">Gratis</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-2">
                        <span class="font-bold text-gray-800">Total Harga</span>
                        <span class="text-xl font-black text-indigo-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('user.checkout') }}" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg shadow-indigo-200 transition-all text-sm">
                        Lanjut ke Pembayaran <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-dashed border-gray-200 p-12 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="shopping-cart" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Keranjang Belanja Masih Kosong</h3>
            <p class="text-xs text-gray-400 mb-6">Jelajahi produk kami dan temukan barang yang Anda butuhkan!</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 shadow-md shadow-indigo-100 transition-all">
                <i data-lucide="compass" class="w-4 h-4"></i> Lihat Katalog Produk
            </a>
        </div>
    @endif
</div>
@endsection

