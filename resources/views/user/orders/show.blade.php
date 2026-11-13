@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Breadcrumb / Back button -->
    <a href="{{ route('user.orders.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Riwayat Pesanan
    </a>

    <!-- Order Header Card & Status Progress -->
    <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-100">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rincian Transaksi</span>
                <h1 class="text-2xl font-black text-gray-900 font-mono mt-0.5">{{ $order->order_number }}</h1>
                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    Dibuat pada {{ ($order->placed_at ?? $order->created_at)->format('d F Y, H:i') }} WIB
                </p>
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
                        'completed' => 'Pesanan Selesai',
                        'cancelled' => 'Dibatalkan',
                    ];
                @endphp
                <span class="px-4 py-2 rounded-2xl text-xs font-black uppercase {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <!-- Tracking Timeline Stepper (if not cancelled) -->
        @if($order->status !== 'cancelled')
            @php
                $steps = [
                    'pending' => ['title' => 'Menunggu Bayar', 'icon' => 'credit-card', 'step' => 1],
                    'paid' => ['title' => 'Terverifikasi', 'icon' => 'check-circle-2', 'step' => 2],
                    'processing' => ['title' => 'Diproses Gudang', 'icon' => 'package', 'step' => 3],
                    'shipped' => ['title' => 'Dalam Pengiriman', 'icon' => 'truck', 'step' => 4],
                    'completed' => ['title' => 'Pesanan Selesai', 'icon' => 'check-check', 'step' => 5],
                ];

                $currentStep = match($order->status) {
                    'pending' => 1,
                    'paid' => 2,
                    'processing' => 3,
                    'shipped' => 4,
                    'completed' => 5,
                    default => 1,
                };
            @endphp
            <div class="pt-2">
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    @foreach($steps as $key => $stepData)
                        <div class="flex items-center gap-2.5 p-3 rounded-2xl {{ $currentStep >= $stepData['step'] ? 'bg-indigo-50/80 border border-indigo-100' : 'bg-gray-50/50 border border-gray-100 opacity-60' }}">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ $currentStep >= $stepData['step'] ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-gray-200 text-gray-400' }}">
                                <i data-lucide="{{ $stepData['icon'] }}" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold uppercase tracking-wider block {{ $currentStep >= $stepData['step'] ? 'text-indigo-600' : 'text-gray-400' }}">Tahap {{ $stepData['step'] }}</span>
                                <span class="text-xs font-bold text-gray-800 truncate block">{{ $stepData['title'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-800 text-xs font-semibold">
                <i data-lucide="x-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                Pesanan ini telah dibatalkan. Jika Anda memiliki pertanyaan, silakan hubungi tim layanan pelanggan kami.
            </div>
        @endif
    </div>

    <!-- Payment CTA Banner if pending / waiting verification / rejected -->
    @if($order->status === 'pending')
        @if($order->payment?->status === 'waiting_verification')
            <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-amber-900 text-sm">Bukti Pembayaran Sedang Diverifikasi</h4>
                        <p class="text-xs text-amber-700 mt-0.5">Kami sedang memeriksa bukti pembayaran Anda. Status pesanan akan otomatis berubah setelah berhasil diverifikasi.</p>
                    </div>
                </div>
                <a href="{{ route('user.payment.show', $order) }}" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-2xl text-xs transition-colors shadow-sm self-start sm:self-auto shrink-0">
                    Lihat Bukti Transfer
                </a>
            </div>
        @elseif($order->payment?->status === 'rejected')
            <div class="bg-red-50 border border-red-200 rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0">
                        <i data-lucide="alert-octagon" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-red-900 text-sm">Bukti Pembayaran Ditolak</h4>
                        <p class="text-xs text-red-700 mt-0.5">Alasan: {{ $order->payment->rejection_reason ?? 'Bukti tidak sesuai.' }}</p>
                    </div>
                </div>
                <a href="{{ route('user.payment.show', $order) }}" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl text-xs transition-colors shadow-sm self-start sm:self-auto shrink-0">
                    Upload Ulang Bukti
                </a>
            </div>
        @else
            <div class="bg-gradient-to-r from-indigo-500 to-violet-600 text-white rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6 shadow-lg shadow-indigo-100">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-indigo-100 uppercase tracking-wider">Menunggu Pembayaran</span>
                    <h3 class="text-xl font-bold text-white">Selesaikan Pembayaran Sekarang</h3>
                    <p class="text-xs text-indigo-100">Segera lakukan transfer dan upload bukti agar pesanan Anda dapat diproses secepatnya.</p>
                </div>
                <a href="{{ route('user.payment.show', $order) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 text-indigo-600 font-bold rounded-2xl text-sm shadow-md transition-all self-start sm:self-auto shrink-0">
                    <i data-lucide="credit-card" class="w-4 h-4"></i> Bayar Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </a>
            </div>
        @endif
    @endif

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Items & Delivery Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Card -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 text-base">Barang yang Dipesan</h3>
                    <span class="text-xs font-semibold text-gray-400">{{ $order->items->sum('quantity') }} total barang</span>
                </div>

                <div class="divide-y divide-gray-50 p-6">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                            @if($item->product)
                                <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product_name }}" class="w-16 h-16 rounded-2xl object-cover bg-gray-100 shrink-0 border border-gray-100">
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center shrink-0 text-gray-400">
                                    <i data-lucide="package" class="w-7 h-7"></i>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-900 text-sm truncate">{{ $item->product_name }}</h4>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="text-right">
                                <span class="font-bold text-gray-900 text-sm">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm space-y-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-5 h-5 text-indigo-600"></i>
                    <h3 class="font-bold text-gray-900 text-base">Alamat Tujuan Pengiriman</h3>
                </div>

                @if($order->address)
                    <div class="text-xs text-gray-600 space-y-1.5 bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900 text-sm">{{ $order->address->recipient_name }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700">
                                {{ $order->address->label }}
                            </span>
                        </div>
                        <p class="font-mono text-indigo-600">{{ $order->address->phone }}</p>
                        <p class="leading-relaxed">{{ $order->address->address }}</p>
                        <p>{{ $order->address->district }}, {{ $order->address->city }}, {{ $order->address->province }} {{ $order->address->postal_code }}</p>
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">Informasi alamat tidak ditemukan.</p>
                @endif

                @if($order->notes)
                    <div class="pt-3 border-t border-gray-100">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Catatan Anda:</span>
                        <p class="text-xs text-gray-700 italic mt-1 bg-amber-50/60 p-3 rounded-xl border border-amber-100">
                            "{{ $order->notes }}"
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Summary & Payment -->
        <div class="space-y-6">
            <!-- Payment Summary Card -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 text-base">Ringkasan Biaya</h3>

                <div class="space-y-2.5 text-xs text-gray-600 pb-4 border-b border-gray-100">
                    <div class="flex justify-between">
                        <span>Total Harga Barang</span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ongkos Kirim</span>
                        <span class="font-semibold text-emerald-600">Gratis (Rp 0)</span>
                    </div>
                </div>

                <div class="flex justify-between items-center text-sm font-bold text-gray-900">
                    <span>Total Pembayaran</span>
                    <span class="text-xl font-black text-indigo-600">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>

                <div class="pt-3 border-t border-gray-100 space-y-2 text-xs">
                    <div class="flex justify-between items-center text-gray-500">
                        <span>Metode Pembayaran:</span>
                        <span class="font-bold uppercase text-gray-800">{{ $order->payment?->method ?? 'QRIS' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-500">
                        <span>Status Pembayaran:</span>
                        @php
                            $pStatus = $order->payment?->status ?? 'pending';
                            $pBadges = [
                                'pending' => 'bg-amber-100 text-amber-800',
                                'waiting_verification' => 'bg-blue-100 text-blue-800',
                                'verified' => 'bg-emerald-100 text-emerald-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                            $pLabels = [
                                'pending' => 'Belum Lunas',
                                'waiting_verification' => 'Menunggu Verifikasi',
                                'verified' => 'Lunas',
                                'rejected' => 'Ditolak',
                            ];
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[11px] {{ $pBadges[$pStatus] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $pLabels[$pStatus] ?? ucfirst($pStatus) }}
                        </span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('user.payment.show', $order) }}" class="w-full flex items-center justify-center gap-2 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-2xl font-bold text-xs transition-colors">
                        <i data-lucide="receipt" class="w-4 h-4"></i> Rincian Pembayaran
                    </a>
                </div>
            </div>

            <!-- Help / Info Card -->
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-200/60 space-y-3">
                <div class="flex items-center gap-2 text-gray-800 font-bold text-xs">
                    <i data-lucide="help-circle" class="w-4 h-4 text-indigo-600"></i>
                    <span>Butuh Bantuan Pesanan?</span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Apabila ada kendala dengan barang atau pembayaran Anda, tim dukungan kami siap membantu setiap hari kerja.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

