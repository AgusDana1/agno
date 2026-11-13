@extends('layouts.admin')

@section('page_title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Kategori
    </a>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
        <div>
            <h3 class="font-bold text-gray-900 text-lg">Form Tambah Kategori</h3>
            <p class="text-xs text-gray-500 mt-0.5">Isi data kategori di bawah ini</p>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Sepatu Olahraga" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm">
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1">Deskripsi (Opsional)</label>
                <textarea name="description" rows="3" placeholder="Deskripsi singkat kategori..." class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 resize-none text-sm">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="is_active" name="is_active" value="1" checked class="w-4 h-4 text-indigo-600 rounded">
                <label for="is_active" class="text-xs font-medium text-gray-700 cursor-pointer">Status Aktif (Ditampilkan di katalog toko)</label>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
