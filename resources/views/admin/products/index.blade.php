@extends('layouts.admin')

@section('page_title', 'Kelola Produk')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Produk</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kelola seluruh inventaris barang dagangan di toko</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-100 transition-all self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Produk Baru
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[200px]">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama produk..." 
                    class="w-full pl-9 pr-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-600 outline-none"
                >
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <select name="category" class="text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 outline-none focus:border-indigo-600 text-gray-700">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl">
                Filter
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('admin.products.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 uppercase text-gray-400 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-5">Produk</th>
                        <th class="py-3 px-5">Kategori</th>
                        <th class="py-3 px-5">Harga</th>
                        <th class="py-3 px-5">Stok</th>
                        <th class="py-3 px-5">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100 shrink-0">
                                    <div>
                                        <p class="font-bold text-gray-900 line-clamp-1">{{ $product->name }}</p>
                                        <p class="text-[11px] text-gray-400 font-mono">{{ $product->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 font-semibold text-[11px]">
                                    {{ $product->category?->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 font-bold text-gray-900">
                                {{ $product->formatted_price }}
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-bold {{ $product->stock > 5 ? 'text-gray-800' : 'text-red-600' }}">
                                    {{ $product->stock }} unit
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $product->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors" title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                Tidak ada produk yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

