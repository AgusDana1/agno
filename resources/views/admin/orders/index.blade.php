@extends('layouts.admin')

@section('page_title', 'Kelola Pesanan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Semua Pesanan</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kelola seluruh riwayat transaksi dan status pemrosesan pesanan pelanggan</p>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[220px] flex-1 sm:flex-none">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari no. order atau nama pelanggan..." 
                    class="w-full pl-9 pr-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-600 outline-none"
                >
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <select name="status" class="text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 outline-none focus:border-indigo-600 text-gray-700">
                <option value="">Semua Status Pesanan</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu Pembayaran)</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid (Sudah Dibayar)</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing (Sedang Diproses)</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped (Dalam Pengiriman)</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                Filter
            </button>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 uppercase text-gray-400 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-5">Nomor Pesanan</th>
                        <th class="py-3 px-5">Pelanggan</th>
                        <th class="py-3 px-5">Tanggal Dipesan</th>
                        <th class="py-3 px-5">Total Pembayaran</th>
                        <th class="py-3 px-5">Status Pesanan</th>
                        <th class="py-3 px-5">Status Bayar</th>
                        <th class="py-3 px-5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-5">
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-mono font-bold text-indigo-600 hover:text-indigo-800">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="py-4 px-5">
                                <p class="font-bold text-gray-900">{{ $order->user?->name ?? 'User #' . $order->user_id }}</p>
                                <p class="text-gray-400 text-[11px]">{{ $order->user?->email }}</p>
                            </td>
                            <td class="py-4 px-5 text-gray-500 whitespace-nowrap">
                                {{ ($order->placed_at ?? $order->created_at)->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-5 font-bold text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap">
                                @php
                                    $orderBadges = [
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'paid' => 'bg-blue-100 text-blue-800',
                                        'processing' => 'bg-indigo-100 text-indigo-800',
                                        'shipped' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-emerald-100 text-emerald-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 text-[11px] font-bold uppercase rounded-full {{ $orderBadges[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap">
                                @php
                                    $paymentStatus = $order->payment?->status;
                                    $paymentBadges = [
                                        'pending' => 'bg-gray-100 text-gray-700',
                                        'waiting_verification' => 'bg-amber-100 text-amber-800',
                                        'verified' => 'bg-emerald-100 text-emerald-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $paymentLabels = [
                                        'pending' => 'Belum Bayar',
                                        'waiting_verification' => 'Verifikasi',
                                        'verified' => 'Lunas',
                                        'rejected' => 'Ditolak',
                                    ];
                                @endphp
                                @if($paymentStatus)
                                    <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full {{ $paymentBadges[$paymentStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $paymentLabels[$paymentStatus] ?? ucfirst($paymentStatus) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl transition-colors">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                                Tidak ada data pesanan yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

