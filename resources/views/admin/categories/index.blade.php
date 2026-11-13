@extends('layouts.admin')

@section('page_title', 'Kelola Kategori')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Kategori Produk</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kelola kategori untuk mengorganisir katalog produk toko</p>
        </div>

        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-100 transition-all self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kategori
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 uppercase text-gray-400 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-5">Nama Kategori</th>
                        <th class="py-3 px-5">Slug</th>
                        <th class="py-3 px-5">Deskripsi</th>
                        <th class="py-3 px-5">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3.5 px-5 font-bold text-gray-900">{{ $category->name }}</td>
                            <td class="py-3.5 px-5 font-mono text-gray-500">{{ $category->slug }}</td>
                            <td class="py-3.5 px-5 text-gray-500 max-w-xs truncate">{{ $category->description ?? '-' }}</td>
                            <td class="py-3.5 px-5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $category->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors" title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
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
                            <td colspan="5" class="py-12 text-center text-gray-400">
                                Belum ada kategori yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

