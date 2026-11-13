@extends('layouts.pegawai')

@section('page_title', 'Dashboard Pegawai')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Pesanan Perlu Diproses</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $pendingOrders }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                <i data-lucide="credit-card" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Verifikasi Pembayaran</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $waitingPayments }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i data-lucide="truck" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Dalam Pengiriman</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $shippedOrders }}</h3>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('pegawai.orders.index') }}" class="p-6 bg-white rounded-2xl border border-gray-200 shadow-sm hover:border-sky-500 transition-all flex items-center justify-between group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">Kelola Status Pesanan</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Ubah status pesanan ke Sedang Diproses, Dikirim, atau Selesai.</p>
                </div>
            </div>
            <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400 group-hover:text-sky-600 transition-colors"></i>
        </a>

        <a href="{{ route('pegawai.payments.index') }}" class="p-6 bg-white rounded-2xl border border-gray-200 shadow-sm hover:border-sky-500 transition-all flex items-center justify-between group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <i data-lucide="check-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">Verifikasi Pembayaran</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Periksa bukti transfer dan validasi pembayaran pesanan.</p>
                </div>
            </div>
            <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400 group-hover:text-sky-600 transition-colors"></i>
        </a>
    </div>

    <!-- Recent Orders Table -->
    @if(isset($recentOrders) && $recentOrders->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm">Pesanan Terbaru Masuk</h3>
                <a href="{{ route('pegawai.orders.index') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700">Lihat Semua</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-gray-50 uppercase text-gray-400 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="py-3 px-5">No. Pesanan</th>
                            <th class="py-3 px-5">Pelanggan</th>
                            <th class="py-3 px-5">Total</th>
                            <th class="py-3 px-5">Status</th>
                            <th class="py-3 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3.5 px-5 font-bold text-gray-900 font-mono">{{ $order->order_number }}</td>
                                <td class="py-3.5 px-5">{{ $order->user?->name ?? '-' }}</td>
                                <td class="py-3.5 px-5 font-semibold text-gray-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="py-3.5 px-5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $order->status === 'paid' ? 'bg-blue-100 text-blue-800' : ($order->status === 'processing' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700') }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <a href="{{ route('pegawai.orders.show', $order) }}" class="text-sky-600 hover:text-sky-800 font-bold">Proses</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

