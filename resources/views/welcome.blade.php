@extends('layouts.app')

@section('content')
<div class="space-y-16 py-8">
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white p-8 sm:p-12 lg:p-16 shadow-2xl">
            <!-- Background Glow & Shapes -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-violet-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/10 text-xs font-semibold text-indigo-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Toko Online Modern & Terpercaya
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
                    Belanja Mudah, Cepat, dan <span class="bg-gradient-to-r from-indigo-400 to-violet-300 bg-clip-text text-transparent">Hemat Setiap Hari</span>
                </h1>

                <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-xl">
                    Temukan produk berkualitas terbaik pilihan kami dengan proses transaksi instan melalui QRIS & Transfer Bank, serta kemudahan pelacakan pesanan secara real-time.
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 transition-all hover:scale-[1.02]">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        Jelajahi Produk
                    </a>

                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm backdrop-blur border border-white/15 transition-all">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            Daftar Akun Baru
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm backdrop-blur border border-white/15 transition-all">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            Buka Dashboard
                        </a>
                    @endguest
                </div>

                <!-- Trust Badges -->
                <div class="pt-6 border-t border-white/10 grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs text-slate-400">
                    <div class="flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                        <span>100% Produk Original</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="truck" class="w-4 h-4 text-indigo-400 shrink-0"></i>
                        <span>Pengiriman Cepat</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-4 h-4 text-violet-400 shrink-0"></i>
                        <span>QRIS & Transfer Aman</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    @if(isset($categories) && $categories->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Kategori Pilihan</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Cari Berdasarkan Kategori</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                    Semua Kategori <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="group bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all flex flex-col items-center text-center space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-colors">
                            <i data-lucide="tag" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-xs sm:text-sm group-hover:text-indigo-600 transition-colors line-clamp-1">
                                {{ $category->name }}
                            </h3>
                            <span class="text-[11px] text-gray-400 font-medium">
                                {{ $category->products_count ?? $category->products()->count() }} Produk
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Featured Products Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Katalog Unggulan</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-1">Produk Terbaru & Terlaris</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                Lihat Selengkapnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        @if(isset($featuredProducts) && $featuredProducts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden group">
                        <!-- Product Image -->
                        <div class="relative aspect-square overflow-hidden bg-gray-100">
                            <img 
                                src="{{ $product->primary_image_url }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                            @if($product->category)
                                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur px-2.5 py-1 rounded-xl text-[10px] font-bold text-gray-700 shadow-sm">
                                    {{ $product->category->name }}
                                </span>
                            @endif

                            @if($product->stock <= 0)
                                <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px] flex items-center justify-center">
                                    <span class="px-3 py-1 bg-red-600 text-white font-bold text-xs rounded-xl uppercase tracking-wider">
                                        Stok Habis
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm hover:text-indigo-600 transition-colors line-clamp-2">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                @if($product->description)
                                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">
                                        {{ $product->description }}
                                    </p>
                                @endif
                            </div>

                            <div class="pt-2 border-t border-gray-50 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-gray-400 block font-medium">Harga</span>
                                    <span class="text-base font-black text-indigo-600">
                                        {{ $product->formatted_price }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('products.show', $product->slug) }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors" title="Lihat Rincian">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>

                                    @if($product->stock > 0)
                                        @auth
                                            <form action="{{ route('user.cart.add', $product) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="p-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-sm transition-colors" title="Tambah ke Keranjang">
                                                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}" class="p-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-sm transition-colors" title="Masuk untuk Membeli">
                                                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                            </a>
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 bg-white rounded-3xl border border-gray-100 text-center text-gray-400 max-w-md mx-auto">
                <i data-lucide="package-open" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                <p class="font-bold text-gray-700 text-sm">Belum ada produk aktif yang tersedia</p>
                <p class="text-xs text-gray-400 mt-1">Silakan kembali lagi nanti untuk melihat produk terbaru kami.</p>
            </div>
        @endif
    </section>

    <!-- Value Propositions Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-indigo-50/60 border border-indigo-100/80 rounded-3xl p-8 sm:p-12">
            <div class="text-center max-w-xl mx-auto mb-10">
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Keunggulan Belanja</span>
                <h2 class="text-2xl font-bold text-gray-900 mt-1">Mengapa Memilih AgnoStore?</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-indigo-50 shadow-sm space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm">Produk Terjamin</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Seluruh barang dikurasi secara ketat dan dijamin kualitas orisinalnya.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-indigo-50 shadow-sm space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i data-lucide="qr-code" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm">QRIS & Transfer Mudah</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Pembayaran instan tanpa ribet dengan konfirmasi verifikasi cepat.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-indigo-50 shadow-sm space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i data-lucide="truck" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm">Pengiriman Tepat Waktu</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Packing aman dan dikirim langsung oleh staf gudang profesional.</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-indigo-50 shadow-sm space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                        <i data-lucide="headphones" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm">Layanan Bantuan Sigap</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Tim dukungan kami siap melayani dan menjawab pertanyaan Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Guest CTA Section -->
    @guest
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-gray-200 p-8 sm:p-12 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <h3 class="text-xl sm:text-2xl font-black text-gray-900">Belum Punya Akun di AgnoStore?</h3>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-lg">
                        Daftar sekarang untuk mulai berbelanja, menyimpan alamat pengiriman, dan memantau status pesanan Anda secara praktis.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('login') }}" class="px-5 py-3 rounded-2xl border border-gray-200 hover:border-indigo-600 text-gray-700 hover:text-indigo-600 font-bold text-xs transition-colors">
                        Masuk ke Akun
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition-all">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </section>
    @endguest
</div>
@endsection

