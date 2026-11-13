@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header & Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Katalog Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Jelajahi beragam produk terbaik kami dengan harga kompetitif</p>
        </div>

        <!-- Filter / Search Form -->
        <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <div class="relative min-w-[240px]">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau deskripsi..." 
                    class="w-full pl-10 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all"
                >
                <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                Cari
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('products.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Category Pill Filter -->
    @if(isset($categories) && $categories->count() > 0)
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 no-scrollbar">
            <a 
                href="{{ route('products.index', array_filter(['search' => request('search')])) }}" 
                class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ !request('category') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
            >
                Semua Kategori
            </a>
            @foreach($categories as $cat)
                <a 
                    href="{{ route('products.index', array_filter(['category' => $cat->id, 'search' => request('search')])) }}" 
                    class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ request('category') == $cat->id ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}"
                >
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    @endif

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-200 flex flex-col">
                <a href="{{ route('products.show', $product->slug) }}" class="relative aspect-video bg-gray-100 overflow-hidden block">
                    <img 
                        src="{{ $product->primary_image_url }}" 
                        alt="{{ $product->name }}" 
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='https://placehold.co/600x400/e2e8f0/64748b?text={{ urlencode($product->name) }}'"
                    >
                    @if($product->category)
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur px-2.5 py-1 rounded-lg text-xs font-semibold text-gray-700 shadow-sm">
                            {{ $product->category->name }}
                        </span>
                    @endif
                    <span class="absolute bottom-3 right-3 bg-slate-900/80 backdrop-blur px-2 py-0.5 rounded-md text-xs font-medium text-white shadow-sm">
                        Stok: {{ $product->stock }}
                    </span>
                </a>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <a href="{{ route('products.show', $product->slug) }}">
                            <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-1 mb-1">{{ $product->name }}</h3>
                        </a>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $product->description }}</p>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-400 block">Harga</span>
                            <span class="text-base font-bold text-indigo-600">{{ $product->formatted_price }}</span>
                        </div>

                        @auth
                            <form action="{{ route('user.cart.add', $product) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="p-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white transition-colors" title="Tambah ke keranjang">
                                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="p-2.5 rounded-xl bg-gray-50 hover:bg-indigo-600 text-gray-500 hover:text-white transition-colors" title="Masuk untuk membeli">
                                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-dashed border-gray-200">
                <i data-lucide="package-search" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                <h3 class="font-bold text-gray-700">Tidak ada produk ditemukan</h3>
                <p class="text-sm text-gray-500 mt-1">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 text-xs font-semibold">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Reset Filter
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $products->links() }}
    </div>
</div>
@endsection

