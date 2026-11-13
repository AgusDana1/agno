@extends('layouts.admin')

@section('page_title', 'Kelola Pengguna')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Pengguna & Hak Akses</h2>
            <p class="text-xs text-gray-500 mt-0.5">Kelola akun administrator, staf pegawai operasional, dan pelanggan</p>
        </div>

        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-100 transition-all self-start sm:self-auto">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Tambah Pengguna Baru
        </a>
    </div>

    <!-- Search Form -->
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[220px] flex-1 sm:flex-none">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau email pengguna..." 
                    class="w-full pl-9 pr-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-600 outline-none"
                >
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors">
                Cari
            </button>

            @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 uppercase text-gray-400 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-5">Pengguna</th>
                        <th class="py-3 px-5">Role Akses</th>
                        <th class="py-3 px-5">Terdaftar Sejak</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center shrink-0 text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                            @if($user->id === auth()->id())
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Anda</span>
                                            @endif
                                        </div>
                                        <p class="text-gray-400 text-[11px]">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                @php
                                    $roleName = $user->role?->name ?? 'user';
                                    $roleBadges = [
                                        'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'pegawai' => 'bg-sky-100 text-sky-800 border-sky-200',
                                        'user' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase border {{ $roleBadges[$roleName] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                    {{ $roleName }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-gray-500 whitespace-nowrap">
                                {{ $user->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors" title="Edit Pengguna">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Hapus Pengguna">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400">
                                <i data-lucide="users" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                                Tidak ada data pengguna yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

