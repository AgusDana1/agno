@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pesanan Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau status pesanan, pembayaran, dan riwayat transaksi belanja Anda</p>
        </div>
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl text-xs font-bold transition-colors self-start sm:self-auto">
            <i data-lucide="shopping-bag" class="w-4 h-4"></i> Lanjut Belanja
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    <!-- Order Card Header -->
                    <div class="p-5 sm:px-6 bg-gray-50/60 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="font-bold text-gray-900 font-mono text-sm">{{ $order->order_number }}</span>
                            <span class="text-gray-400">|</span>
                            <span class="text-gray-500 flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-gray-400"></i>
                                {{ ($order->placed_at ?? $order->created_at)->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <div>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'paid' => 'bg-blue-100 text-blue-800',
                                    'processing' => 'bg-indigo-100 text-indigo-800',
                                    'shipped' => 'bg-purple-100 text-purple-800',
                                    'completed' => 'bg-emerald-100 text-emerald-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu Pembayaran',
                                    'paid' => 'Sudah Dibayar',
                                    'processing' => 'Sedang Diproses',
                                    'shipped' => 'Dalam Pengiriman',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Card Body: Items Preview -->
                    <div class="p-5 sm:p-6 divide-y divide-gray-50">
                        @foreach($order->items->take(2) as $item)
                            <div class="flex items-center gap-4 py-3 first:pt-0 last:pb-0">
                                @if($item->product)
                                    <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product_name }}" class="w-14 h-14 rounded-2xl object-cover bg-gray-100 shrink-0 border border-gray-100">
                                @else
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center shrink-0 text-gray-400">
                                        <i data-lucide="package" class="w-6 h-6"></i>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900 text-sm truncate">{{ $item->product_name }}</h4>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $item->quantity }} barang x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>

                                <div class="text-right">
                                    <span class="font-bold text-gray-900 text-sm">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        @if($order->items->count() > 2)
                            <div class="pt-3 text-xs text-gray-500 font-medium">
                                + {{ $order->items->count() - 2 }} produk lainnya
                            </div>
                        @endif
                    </div>

                    <!-- Order Card Footer: Total & Actions -->
                    <div class="p-5 sm:px-6 bg-white border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs text-gray-400 block">Total Tagihan:</span>
                            <span class="text-lg font-black text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($order->status === 'pending')
                                @if($order->payment?->status === 'waiting_verification')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-50 text-amber-700 text-xs font-bold rounded-xl border border-amber-200">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i> Verifikasi Bukti
                                    </span>
                                    <a href="{{ route('user.payment.show', $order) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors">
                                        Cek Bukti
                                    </a>
                                @else
                                    <a href="{{ route('user.payment.show', $order) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-200 transition-all">
                                        <i data-lucide="credit-card" class="w-3.5 h-3.5"></i> Bayar Sekarang
                                    </a>
                                @endif
                            @endif

                            <a href="{{ route('user.orders.show', $order) }}" class="inline-flex items-center gap-1 px-4 py-2 border border-gray-200 hover:border-indigo-600 hover:text-indigo-600 text-gray-700 text-xs font-bold rounded-xl transition-colors">
                                Rincian Pesanan <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center max-w-md mx-auto shadow-sm space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto">
                <i data-lucide="package-open" class="w-8 h-8"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Belum Ada Riwayat Pesanan</h3>
                <p class="text-xs text-gray-500 mt-1">Anda belum melakukan pembelian produk di toko kami. Temukan barang pilihanmu sekarang!</p>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-xs shadow-md shadow-indigo-200 transition-all">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Mulai Belanja Sekarang
            </a>
        </div>
    @endif
</div>
@endsection

