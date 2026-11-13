@extends('layouts.admin')

@section('page_title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Pesanan
    </a>

    <!-- Top Card: Status & Update Action -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Pesanan Saat Ini</span>
            <div class="flex flex-wrap items-center gap-3 mt-1">
                <span class="text-xl font-mono font-black text-gray-900">{{ $order->order_number }}</span>
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
                <span class="px-3 py-1 text-xs font-bold uppercase rounded-full {{ $orderBadges[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $order->status }}
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-1">
                Dipesan pada {{ ($order->placed_at ?? $order->created_at)->format('d F Y, H:i') }} WIB oleh 
                <span class="font-semibold text-gray-800">{{ $order->user?->name }}</span> ({{ $order->user?->email }})
            </p>
        </div>

        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex flex-wrap items-center gap-3 bg-gray-50 p-2.5 rounded-xl border border-gray-200">
            @csrf
            @method('PATCH')
            <label class="text-xs font-bold text-gray-600 shrink-0">Ubah Status:</label>
            <select name="status" class="text-xs font-bold bg-white border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-indigo-600 text-gray-800">
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Bayar)</option>
                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid (Terbayar)</option>
                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Sedang Diproses)</option>
                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped (Dikirimkan)</option>
                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs shadow-sm transition-all">
                Simpan
            </button>
        </form>
    </div>

    <!-- Main Grid: Items & Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Items Table -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 text-sm">Rincian Barang Belanjaan</h3>
                    <span class="text-xs text-gray-400">{{ $order->items->count() }} item</span>
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
                                    <td class="py-3.5 px-5">
                                        <div class="flex items-center gap-3">
                                            @if($item->product)
                                                <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product_name }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100 shrink-0">
                                            @endif
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $item->product_name }}</p>
                                                @if($item->product)
                                                    <p class="text-[11px] text-gray-400 font-mono">{{ $item->product->slug }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-5 text-center font-bold text-gray-800">{{ $item->quantity }} pcs</td>
                                    <td class="py-3.5 px-5 text-right whitespace-nowrap">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-5 text-right font-bold text-gray-900 whitespace-nowrap">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-gray-50 border-t border-gray-100 space-y-2 text-xs">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal Belanja:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkos Kirim:</span>
                        <span class="font-semibold text-emerald-600">Gratis (Rp 0)</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-gray-900 pt-2 border-t border-gray-200">
                        <span>Total Tagihan:</span>
                        <span class="text-indigo-600 font-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($order->notes)
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Catatan Pembeli</span>
                    <p class="text-xs text-gray-700 mt-2 bg-amber-50 border border-amber-200 p-3.5 rounded-xl italic">
                        "{{ $order->notes }}"
                    </p>
                </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Shipping Address -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-3">
                <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-indigo-600"></i> Alamat Pengiriman
                </h4>
                @if($order->address)
                    <div class="text-xs text-gray-600 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-800">{{ $order->address->recipient_name }}</span>
                            <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[10px] font-bold uppercase">{{ $order->address->label }}</span>
                        </div>
                        <p class="text-indigo-600 font-mono">{{ $order->address->phone }}</p>
                        <p class="leading-relaxed">{{ $order->address->address }}</p>
                        <p>{{ $order->address->district }}, {{ $order->address->city }}</p>
                        <p>{{ $order->address->province }} {{ $order->address->postal_code }}</p>
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">Data alamat pengiriman tidak ditemukan.</p>
                @endif
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm space-y-3 text-xs" x-data="{ rejectOpen: false }">
                <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2 mb-3">
                    <i data-lucide="credit-card" class="w-4 h-4 text-indigo-600"></i> Informasi Pembayaran
                </h4>

                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-500">Metode</span>
                    <span class="font-bold uppercase text-gray-800">{{ $order->payment?->method ?? 'QRIS' }}</span>
                </div>

                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-500">Status Pembayaran</span>
                    @php
                        $payStatus = $order->payment?->status ?? 'pending';
                        $payBadges = [
                            'pending' => 'bg-gray-100 text-gray-700',
                            'waiting_verification' => 'bg-amber-100 text-amber-800',
                            'verified' => 'bg-emerald-100 text-emerald-800',
                            'rejected' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="px-2 py-0.5 font-bold uppercase text-[10px] rounded-full {{ $payBadges[$payStatus] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $payStatus }}
                    </span>
                </div>

                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-500">Nominal Tagihan</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($order->payment?->amount ?? $order->total_amount, 0, ',', '.') }}</span>
                </div>

                @if($order->payment?->proof_path)
                    <div class="pt-2">
                        <span class="text-gray-500 block mb-1.5 font-semibold">Bukti Pembayaran:</span>
                        <a href="{{ asset('storage/' . $order->payment->proof_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl font-bold transition-colors w-full justify-center">
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Buka Bukti Transfer
                        </a>
                    </div>
                @endif

                @if($order->payment && $order->payment->status === 'waiting_verification')
                    <div class="pt-3 border-t border-gray-100 flex items-center gap-2">
                        <form action="{{ route('admin.payments.verify', $order->payment) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('Verifikasi pembayaran pesanan ini?')" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-colors">
                                Verifikasi
                            </button>
                        </form>
                        <button @click="rejectOpen = true" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-bold rounded-xl text-xs transition-colors">
                            Tolak
                        </button>
                    </div>

                    <!-- Reject Modal -->
                    <div x-show="rejectOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 text-left space-y-4" @click.away="rejectOpen = false">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-gray-900 text-base">Tolak Bukti Pembayaran</h4>
                                <button @click="rejectOpen = false" class="text-gray-400 hover:text-gray-600">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>

                            <form action="{{ route('admin.payments.reject', $order->payment) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Penolakan</label>
                                    <textarea name="rejection_reason" required rows="3" placeholder="Contoh: Bukti transfer tidak jelas / nominal salah..." class="w-full p-3 border border-gray-200 rounded-xl text-xs outline-none focus:border-red-500"></textarea>
                                </div>

                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="rejectOpen = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                                    <button type="submit" class="px-4 py-2 text-xs font-bold bg-red-600 hover:bg-red-700 text-white rounded-xl">Kirim Penolakan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                @if($order->payment?->rejection_reason)
                    <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 mt-2">
                        <span class="font-bold block mb-0.5">Alasan Penolakan:</span>
                        <p class="text-[11px]">{{ $order->payment->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

