@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100">
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-100">
                <i data-lucide="log-in" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Selamat Datang Kembali</h2>
            <p class="mt-2 text-sm text-gray-500">Masuk ke akun Anda untuk mulai berbelanja atau mengelola toko</p>
        </div>

        <!-- 1-Click Demo Login Helper -->
        <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-4">
            <p class="text-xs font-semibold text-indigo-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                <i data-lucide="zap" class="w-4 h-4 text-indigo-600"></i> Uji Coba Cepat (1-Click Demo Login)
            </p>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="fillLogin('admin@agno.test', 'Admin123456')" class="px-2.5 py-1.5 text-xs font-semibold bg-purple-600 hover:bg-purple-700 text-white rounded-xl shadow-sm transition-all text-center">
                    Admin
                </button>
                <button type="button" onclick="fillLogin('pegawai@agno.test', 'Pegawai123456')" class="px-2.5 py-1.5 text-xs font-semibold bg-sky-600 hover:bg-sky-700 text-white rounded-xl shadow-sm transition-all text-center">
                    Pegawai
                </button>
                <button type="button" onclick="fillLogin('user@agno.test', 'User123')" class="px-2.5 py-1.5 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm transition-all text-center">
                    Customer
                </button>
            </div>
        </div>

        <form class="mt-6 space-y-5" action="{{ route('login.store') }}" method="POST">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                <div class="relative">
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        value="{{ old('email') }}"
                        placeholder="contoh@domain.com"
                        class="w-full px-4 py-3 pl-11 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all"
                    >
                    <i data-lucide="mail" class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                <div class="relative">
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        autocomplete="current-password" 
                        required 
                        placeholder="••••••••"
                        class="w-full px-4 py-3 pl-11 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all"
                    >
                    <i data-lucide="lock" class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-gray-600 text-sm">Ingat saya</span>
                </label>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-md shadow-indigo-200 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Masuk Sekarang
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-500">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline">
                Daftar akun baru
            </a>
        </p>
    </div>
</div>

<script>
    function fillLogin(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
