@extends('layouts.admin')

@section('page_title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Pengguna
    </a>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
        <div>
            <h3 class="font-bold text-gray-900 text-lg">Buat Akun Pengguna Baru</h3>
            <p class="text-xs text-gray-500 mt-0.5">Daftarkan pengguna baru dan tetapkan role hak aksesnya</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: John Doe" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="nama@email.com" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Role / Peran</label>
                    <select name="role_id" required class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm text-gray-800 bg-white">
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required minlength="8" placeholder="Ulangi password" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 text-sm">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-colors">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

