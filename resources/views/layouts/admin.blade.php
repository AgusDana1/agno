<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} - Agno Store</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
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
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-100 transition-transform duration-200 ease-in-out md:static md:translate-x-0 flex flex-col shrink-0"
        >
            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center text-white font-bold">
                        A
                    </div>
                    <span class="font-bold text-lg text-white">Agno<span class="text-indigo-400">Admin</span></span>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                </a>

                <div class="pt-4 pb-1 px-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Katalog & Produk
                </div>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="folder-tree" class="w-5 h-5"></i> Kategori
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="box" class="w-5 h-5"></i> Produk
                </a>

                <div class="pt-4 pb-1 px-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Transaksi
                </div>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i> Pesanan
                </a>

                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="credit-card" class="w-5 h-5"></i> Verifikasi Bayar
                </a>

                <div class="pt-4 pb-1 px-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                    Sistem
                </div>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i data-lucide="users" class="w-5 h-5"></i> Pengguna & Role
                </a>
            </nav>

            <!-- Bottom Store link -->
            <div class="p-4 border-t border-slate-800">
                <a href="{{ url('/') }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 px-3 text-xs font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">
                    <i data-lucide="external-link" class="w-4 h-4"></i> Buka Storefront
                </a>
            </div>
        </aside>

        <!-- Overlay for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-40 bg-black/50 md:hidden"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-10">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <h1 class="text-lg font-bold text-gray-800">@yield('page_title', 'Admin Panel')</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-indigo-600 font-medium">Administrator</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Keluar">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Body Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                @if(session('success'))
                    <div class="flex items-center gap-3 p-4 mb-6 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 shadow-sm" role="alert">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm" role="alert">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm" role="alert">
                        <div class="font-semibold mb-1">Periksa kembali input Anda:</div>
                        <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
