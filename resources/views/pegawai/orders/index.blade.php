@extends('layouts.pegawai')

@section('page_title', 'Kelola Pesanan Operasional')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Pesanan Gudang</h2>
            <p class="text-xs text-gray-500 mt-0.5">Pesanan dengan status sudah dibayar, sedang diproses, atau dikirim</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 uppercase text-gray-400 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-5">Nomor Pesanan</th>
                        <th class="py-3 px-5">Pelanggan</th>
                        <th class="py-3 px-5">Tanggal Masuk</th>
                        <th class="py-3 px-5">Total Pembayaran</th>
                        <th class="py-3 px-5">Status Saat Ini</th>
                        <th class="py-3 px-5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-5 font-mono font-bold text-gray-900">{{ $order->order_number }}</td>
                            <td class="py-4 px-5">
                                <p class="font-semibold text-gray-800">{{ $order->user?->name ?? 'User #' . $order->user_id }}</p>
                                <p class="text-gray-400 text-[11px]">{{ $order->user?->email }}</p>
                            </td>
                            <td class="py-4 px-5 text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="py-4 px-5 font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="py-4 px-5">
                                @php
                                    $badge = match($order->status) {
                                        'paid' => 'bg-blue-100 text-blue-800',
                                        'processing' => 'bg-indigo-100 text-indigo-800',
                                        'shipped' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-emerald-100 text-emerald-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-[11px] font-bold uppercase rounded-full {{ $badge }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-right">
                                <a href="{{ route('pegawai.orders.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-sky-50 text-sky-600 hover:bg-sky-100 font-bold rounded-lg transition-colors">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Proses
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <i data-lucide="package-check" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                                Tidak ada antrean pesanan yang memerlukan tindakan operasional saat ini.
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

