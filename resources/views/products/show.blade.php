@extends('layouts.pegawai')

@section('page_title', 'Proses Pesanan #' . $order->order_number)

@section('content')
<div class="space-y-6">
    <a href="{{ route('pegawai.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-sky-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Antrean Pesanan
    </a>

    <!-- Top Action Card: Update Status -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Operasional Saat Ini</span>
            <div class="flex items-center gap-3 mt-1">
                <span class="text-xl font-mono font-black text-gray-900">{{ $order->order_number }}</span>
                <span class="px-3 py-1 text-xs font-bold uppercase rounded-full bg-sky-100 text-sky-800">
                    {{ $order->status }}
                </span>
            </div>
            <p class="text-xs text-gray-400 mt-0.5">Dipesan oleh {{ $order->user?->name }} ({{ $order->user?->email }})</p>
        </div>

        <form action="{{ route('pegawai.orders.status', $order) }}" method="POST" class="flex items-center gap-3 bg-gray-50 p-2.5 rounded-xl border border-gray-200">
            @csrf
            @method('PATCH')
            <label class="text-xs font-bold text-gray-600 shrink-0">Ubah Status:</label>
            <select name="status" class="text-xs font-bold bg-white border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-sky-500 text-gray-800">
                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Sedang Diproses (Packaging)</option>
                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim ke Kurir</option>
                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Pesanan Selesai (Sampai)</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg text-xs shadow-sm transition-all">
                Simpan
            </button>
        </form>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Items table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 text-sm">Daftar Barang untuk Disiapkan</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-gray-50 text-gray-400 uppercase font-semibold">
                        <tr>
                            <th class="py-3 px-5">Produk</th>
                            <th class="py-3 px-5 text-center">Jumlah</th>
                            <th class="py-3 px-5 text-right">Harga Satuan</th>
                            <th class="py-3 px-5 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-3.5 px-5 font-bold text-gray-800">{{ $item->product_name }}</td>
                                <td class="py-3.5 px-5 text-center font-bold text-sky-600">{{ $item->quantity }} pcs</td>
                                <td class="py-3.5 px-5 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="py-3.5 px-5 text-right font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Address & Customer Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-3">
                <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-sky-600"></i> Tujuan Pengiriman
                </h4>
                @if($order->address)
                    <div class="text-xs text-gray-600 space-y-1">
                        <p class="font-bold text-gray-800">{{ $order->address->recipient_name }}</p>
                        <p class="text-sky-600 font-mono">{{ $order->address->phone }}</p>
                        <p>{{ $order->address->address }}</p>
                        <p>{{ $order->address->district }}, {{ $order->address->city }}</p>
                        <p>{{ $order->address->province }} {{ $order->address->postal_code }}</p>
                    </div>
                @else
                    <p class="text-xs text-gray-400">Alamat tidak tercantum.</p>
                @endif

                @if($order->notes)
                    <div class="pt-3 border-t border-gray-100">
                        <span class="text-[11px] font-bold uppercase text-gray-400">Catatan Khusus:</span>
                        <p class="text-xs italic text-gray-700 mt-1">"{{ $order->notes }}"</p>
                    </div>
                @endif
            </div>

            <!-- Payment Summary -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-2 text-xs">
                <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2 mb-3">
                    <i data-lucide="credit-card" class="w-4 h-4 text-sky-600"></i> Status Pembayaran
                </h4>
                <div class="flex justify-between">
                    <span class="text-gray-500">Metode</span>
                    <span class="font-bold uppercase text-gray-800">{{ $order->payment?->method ?? 'QRIS' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="font-bold text-emerald-600">{{ $order->payment?->status ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-t border-gray-100 pt-2 font-bold text-sm">
                    <span class="text-gray-800">Total Tagihan</span>
                    <span class="text-sky-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

