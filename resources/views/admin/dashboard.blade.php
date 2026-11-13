@extends('layouts.admin')

@section('page_title', 'Dashboard Administrator')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Total Pengguna</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $totalUsers }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i data-lucide="box" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Total Produk</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $totalProducts }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="shopping-bag" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Total Transaksi</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $totalOrders }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase">Pesanan Tertunda</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $pendingOrders }}</h3>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Panels -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.products.index') }}" class="p-6 bg-white rounded-2xl border border-gray-200 shadow-sm hover:border-indigo-500 hover:shadow-md transition-all group flex flex-col justify-between">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <i data-lucide="boxes" class="w-6 h-6"></i>
                </div>
                <h4 class="font-bold text-gray-900">Kelola Produk</h4>
                <p class="text-xs text-gray-500">Tambah produk baru, perbarui stok, atur harga, dan status aktif produk.</p>
            </div>
            <div class="pt-4 mt-4 border-t border-gray-100 flex items-center text-xs font-bold text-indigo-600">
                Buka Katalog Produk <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
            </div>
        </a>

        <a href="{{ route('admin.orders.index') }}" class="p-6 bg-white rounded-2xl border border-gray-200 shadow-sm hover:border-indigo-500 hover:shadow-md transition-all group flex flex-col justify-between">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
                <h4 class="font-bold text-gray-900">Semua Pesanan</h4>
                <p class="text-xs text-gray-500">Pantau seluruh order pelanggan, ubah status pengiriman dan pembayaran.</p>
            </div>
            <div class="pt-4 mt-4 border-t border-gray-100 flex items-center text-xs font-bold text-indigo-600">
                Lihat Pesanan <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
            </div>
        </a>

        <a href="{{ route('admin.users.index') }}" class="p-6 bg-white rounded-2xl border border-gray-200 shadow-sm hover:border-indigo-500 hover:shadow-md transition-all group flex flex-col justify-between">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <i data-lucide="user-cog" class="w-6 h-6"></i>
                </div>
                <h4 class="font-bold text-gray-900">Kelola Pengguna</h4>
                <p class="text-xs text-gray-500">Atur hak akses admin, pegawai operasional, dan data akun pelanggan.</p>
            </div>
            <div class="pt-4 mt-4 border-t border-gray-100 flex items-center text-xs font-bold text-indigo-600">
                Buka Data Pengguna <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
            </div>
        </a>
    </div>
</div>
@endsection

