@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100">
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-100">
                <i data-lucide="user-plus" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Buat Akun Baru</h2>
            <p class="mt-2 text-sm text-gray-500">Bergabung sekarang dan nikmati kemudahan berbelanja online</p>
        </div>

        <form class="mt-6 space-y-5" action="{{ route('register.store') }}" method="POST">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <div class="relative">
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        required 
                        value="{{ old('name') }}"
                        placeholder="Nama Anda"
                        class="w-full px-4 py-3 pl-11 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all"
                    >
                    <i data-lucide="user" class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

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
                        autocomplete="new-password" 
                        required 
                        placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-3 pl-11 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all"
                    >
                    <i data-lucide="lock" class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <input 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        autocomplete="new-password" 
                        required 
                        placeholder="Ulangi kata sandi"
                        class="w-full px-4 py-3 pl-11 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 outline-none transition-all"
                    >
                    <i data-lucide="check-check" class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-md shadow-indigo-200 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                    <i data-lucide="user-check" class="w-4 h-4"></i> Daftar Sekarang
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-500">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline">
                Masuk di sini
            </a>
        </p>
    </div>
</div>
@endsection

