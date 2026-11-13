@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Checkout Pesanan</h1>
        <p class="text-sm text-gray-500 mt-1">Pilih alamat pengiriman dan tinjau rincian pesanan Anda</p>
    </div>

    <form action="{{ route('user.checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Delivery Address & Notes -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Address Section -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-5 h-5 text-indigo-600"></i>
                            <h2 class="text-lg font-bold text-gray-900">Alamat Pengiriman</h2>
                        </div>
                        <a href="{{ route('user.addresses.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah / Kelola Alamat
                        </a>
                    </div>

                    @if($addresses->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($addresses as $addr)
                                <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-indigo-200 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="radio" 
                                                name="address_id" 
                                                value="{{ $addr->id }}" 
                                                {{ ($loop->first || $addr->is_default) ? 'checked' : '' }}
                                                class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"
                                                required
                                            >
                                            <span class="text-xs font-bold uppercase tracking-wider text-gray-700">{{ $addr->label }}</span>
                                        </div>
                                        @if($addr->is_default)
                                            <span class="text-[10px] font-bold bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">Utama</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 text-xs text-gray-600 space-y-1">
                                        <p class="font-semibold text-gray-800">{{ $addr->recipient_name }} ({{ $addr->phone }})</p>
                                        <p class="leading-relaxed">{{ $addr->address }}, {{ $addr->district }}, {{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 bg-amber-50 rounded-2xl border border-amber-200 text-center">
                            <i data-lucide="alert-triangle" class="w-8 h-8 text-amber-500 mx-auto mb-2"></i>
                            <h4 class="font-bold text-amber-900 text-sm">Anda belum memiliki alamat pengiriman</h4>
                            <p class="text-xs text-amber-700 mt-1 mb-4">Tambahkan alamat terlebih dahulu agar pesanan dapat dikirimkan ke lokasi Anda.</p>
                            <a href="{{ route('user.addresses.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Buat Alamat Sekarang
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Notes Section -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm space-y-3">
                    <div class="flex items-center gap-2">
                        <i data-lucide="message-square" class="w-5 h-5 text-indigo-600"></i>
                        <h2 class="text-lg font-bold text-gray-900">Catatan untuk Penjual / Kurir</h2>
                    </div>
                    <textarea 
                        name="notes" 
                        rows="3" 
                        placeholder="Contoh: Titipkan di pos satpam, atau telepon sebelum diantar..."
                        class="w-full p-4 text-sm bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all resize-none"
                    ></textarea>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm sticky top-24 space-y-4">
                    <h3 class="font-bold text-gray-900 text-base">Detail Tagihan</h3>

                    <div class="max-h-60 overflow-y-auto space-y-3 divide-y divide-gray-50 pr-1">
                        @foreach($cart->items as $item)
                            <div class="flex items-center gap-3 pt-3 first:pt-0">
                                <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                                <div class="flex-1 min-w-0 text-xs">
                                    <p class="font-semibold text-gray-800 truncate">{{ $item->product->name }}</p>
                                    <p class="text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <span class="text-xs font-bold text-gray-800">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2 text-sm text-gray-600 border-t border-gray-100 pt-4">
                        <div class="flex justify-between">
                            <span>Subtotal Belanja</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            <span class="text-emerald-600 font-semibold">Gratis</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                        <span class="font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-xl font-black text-indigo-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <button 
                        type="submit" 
                        {{ $addresses->count() === 0 ? 'disabled' : '' }}
                        class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold shadow-lg shadow-indigo-200 transition-all text-sm"
                    >
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Buat Pesanan Sekarang
                    </button>
                    <p class="text-[11px] text-gray-400 text-center">Dengan membuat pesanan, Anda setuju dengan syarat & ketentuan AgnoStore.</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

