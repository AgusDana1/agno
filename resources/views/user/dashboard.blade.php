@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Pengguna</h1>
        <p class="text-sm text-gray-500 mt-1">Halo, <span class="font-semibold text-gray-800">{{ auth()->user()->name }}</span>! Selamat datang di akun AgnoStore Anda.</p>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Total Pesanan</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ auth()->user()->orders()->count() }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="shopping-cart" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Item di Keranjang</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ auth()->user()->cart?->items()->sum('quantity') ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="map-pin" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">Alamat Tersimpan</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ auth()->user()->addresses()->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Pesanan Terakhir</h2>
                <p class="text-xs text-gray-400">5 transaksi terbaru yang Anda lakukan</p>
            </div>
            <a href="{{ route('user.orders.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                Lihat Semua <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/50 text-xs uppercase font-semibold text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-6">Nomor Pesanan</th>
                        <th class="py-3 px-6">Tanggal</th>
                        <th class="py-3 px-6">Total Pembayaran</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-4 px-6 font-semibold text-gray-900 font-mono text-xs">
                                {{ $order->order_number }}
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-800 text-xs">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'paid' => 'bg-blue-100 text-blue-800',
                                        'processing' => 'bg-indigo-100 text-indigo-800',
                                        'shipped' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-emerald-100 text-emerald-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('user.orders.show', $order) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                    Detail <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">
                                <i data-lucide="package-open" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                                <p class="text-sm">Belum ada riwayat pesanan.</p>
                                <a href="{{ route('products.index') }}" class="inline-block mt-3 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-semibold hover:bg-indigo-100">
                                    Mulai Belanja
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

