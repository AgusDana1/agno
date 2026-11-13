<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Agno Store' }} - Marketplace Modern</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans antialiased">
    <!-- Navbar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2 group shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-100 group-hover:scale-105 transition-transform">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Agno</span><span class="text-gray-900 font-bold text-xl">Store</span>
                    </div>
                </a>

                <!-- Search Bar -->
                <div class="flex-1 max-w-md hidden md:block">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Cari produk impianmu..." 
                                class="w-full pl-10 pr-4 py-2 text-sm bg-gray-100 border border-transparent rounded-full focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all outline-none"
                            >
                            <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </form>
                </div>

                <!-- Navigation & Actions -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('products.index') }}" class="hidden sm:inline-flex text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors px-3 py-2 rounded-lg hover:bg-gray-100">
                        Katalog Produk
                    </a>

                    @auth
                        <!-- Cart -->
                        <a href="{{ route('user.cart') }}" class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-full transition-colors" title="Keranjang">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            @php
                                $cartCount = auth()->user()->cart?->items()->sum('quantity') ?? 0;
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white">
                                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                                </span>
                            @endif
                        </a>

                        <!-- User Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-gray-100 transition-colors focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-semibold flex items-center justify-center text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden lg:block text-sm font-medium text-gray-700 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-400">Login sebagai</p>
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full {{ auth()->user()->role?->name === 'admin' ? 'bg-purple-100 text-purple-700' : (auth()->user()->role?->name === 'pegawai' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">
                                        {{ ucfirst(auth()->user()->role?->name ?? 'User') }}
                                    </span>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                        <i data-lucide="shield" class="w-4 h-4 text-indigo-500"></i> Panel Admin
                                    </a>
                                @endif

                                @if(auth()->user()->isPegawai())
                                    <a href="{{ route('pegawai.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                        <i data-lucide="briefcase" class="w-4 h-4 text-indigo-500"></i> Panel Pegawai
                                    </a>
                                @endif

                                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-gray-400"></i> Dashboard Akun
                                </a>
                                <a href="{{ route('user.orders.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i data-lucide="package" class="w-4 h-4 text-gray-400"></i> Pesanan Saya
                                </a>
                                <a href="{{ route('user.addresses.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i> Buku Alamat
                                </a>

                                <div class="border-t border-gray-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 px-3.5 py-2 rounded-xl hover:bg-gray-100 transition-all">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-sm shadow-indigo-200 transition-all">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm" role="alert">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <div class="flex-1 font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-200 shadow-sm" role="alert">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <div class="flex-1 font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-200 shadow-sm" role="alert">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-red-600"></i>
                    Ada beberapa kendala dengan input Anda:
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700 ml-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Agno<span class="text-indigo-600">Store</span></span>
                    </div>
                    <p class="text-gray-500 text-sm max-w-sm mb-4">
                        Platform e-commerce modern, cepat, dan aman dengan manajemen pesanan lengkap untuk pembeli, pegawai gudang, dan administrator.
                    </p>
                    <p class="text-xs text-gray-400">© {{ date('Y') }} AgnoStore Inc. All rights reserved.</p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Belanja</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-indigo-600">Semua Produk</a></li>
                        <li><a href="{{ route('products.index') }}?category=1" class="text-gray-500 hover:text-indigo-600">Elektronik</a></li>
                        <li><a href="{{ route('products.index') }}?category=2" class="text-gray-500 hover:text-indigo-600">Fashion</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Akun & Layanan</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('user.dashboard') }}" class="text-gray-500 hover:text-indigo-600">Dashboard Saya</a></li>
                        <li><a href="{{ route('user.orders.index') }}" class="text-gray-500 hover:text-indigo-600">Lacak Pesanan</a></li>
                        <li><a href="{{ route('user.cart') }}" class="text-gray-500 hover:text-indigo-600">Keranjang Belanja</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>

