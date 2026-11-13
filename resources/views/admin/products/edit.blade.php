@extends('layouts.admin')

@section('page_title', 'Edit Produk: ' . $product->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Produk
    </a>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Edit Spesifikasi Produk</h3>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui rincian, harga, stok, atau kategori produk</p>
            </div>
            @if($product->primary_image_url)
                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-gray-200 shadow-sm">
            @endif
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="name" required value="{{ old('name', $product->name) }}" placeholder="Contoh: Wireless Earbuds Pro" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" required class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm text-gray-800 bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1">Deskripsi Lengkap</label>
                <textarea name="description" rows="4" placeholder="Jelaskan fitur, bahan, keunggulan produk..." class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 resize-none text-sm">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" required min="0" value="{{ old('price', (int)$product->price) }}" placeholder="150000" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm font-semibold">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Jumlah Stok</label>
                    <input type="number" name="stock" required min="0" value="{{ old('stock', $product->stock) }}" placeholder="10" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm font-semibold">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Berat (gram)</label>
                    <input type="number" name="weight" min="0" value="{{ old('weight', (int)$product->weight) }}" placeholder="500" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm">
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                <label for="is_active" class="text-xs font-medium text-gray-700 cursor-pointer">Status Aktif (Tampilkan produk di etalase toko)</label>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

