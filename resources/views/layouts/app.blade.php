<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ setting('app_name', 'Afilia Market') }} | Premium Comfort Marketplace</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        <script src="https://cdn.tailwindcss.com"></script>
        @livewireStyles

        @if(setting('favicon'))
            <link rel="icon" type="image/svg+xml" href="{{ asset('storage/' . setting('favicon')) }}">
        @endif
    </head>
    <body class="font-sans antialiased text-gray-900 bg-white selection:bg-gray-900 selection:text-white hover:cursor-default" x-data="{ sidebarOpen: false }">
        @php
            $isDashboard = auth()->check() && 
                          !auth()->user()->hasRole('customer') && 
                          request()->routeIs(['dashboard', 'admin.*', 'vendor.*', 'profile']);
        @endphp

        <div class="min-h-screen flex">
            <!-- Sidebar (Only for Dashboard areas) -->
            @auth
                @if($isDashboard)
                    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full sm:translate-x-0'" class="fixed top-0 left-0 z-50 w-64 h-screen transition-transform duration-300 transform bg-white border-r border-gray-100">
                        <livewire:layout.sidebar />
                    </div>
                @endif
            @endauth

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen {{ $isDashboard && auth()->check() ? 'sm:ml-64' : '' }}">
                <!-- Header / Navigation -->
                @if($isDashboard && auth()->check())
                    <!-- Dashboard Header -->
                    <header class="sticky top-0 z-40 bg-white border-b border-gray-100 flex items-center justify-between h-16 px-8">
                        <div class="flex items-center gap-6">
                            <button @click="sidebarOpen = !sidebarOpen" class="sm:hidden p-2 text-gray-400 hover:bg-gray-50 rounded-md focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </button>
                            <div class="hidden md:flex items-center text-[10px] font-black text-gray-900 uppercase tracking-[0.2em] gap-3">
                                <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded-sm">SYSTEM</span>
                                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span class="text-gray-400 font-bold tracking-widest">{{ strtoupper(str_replace(['admin', 'vendor', 'products', 'categories', 'orders', 'users', 'dashboard', 'vouchers', 'profile'], ['admin', 'vendor', 'produk', 'kategori', 'pesanan', 'pengguna', 'dashboard', 'voucher', 'pengaturan profil'], str_replace('.', ' / ', request()->route()->getName()))) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <div class="text-right hidden sm:block">
                                <p class="text-[10px] font-black text-gray-900 uppercase tracking-[0.2em] leading-none">{{ auth()->user()->name }}</p>
                                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1.5">{{ auth()->user()->roles->first()->name ?? 'Operator' }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-md bg-gray-900 flex items-center justify-center text-[12px] font-black text-white uppercase border border-gray-800">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                    </header>
                @else
                    <!-- Storefront Navigation -->
                    <livewire:layout.navigation />
                @endif

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>

                <!-- Universal Footer -->
                @if(!$isDashboard)
                <footer class="bg-gray-100 border-t border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 py-16">
                            <div class="col-span-1 md:col-span-2">
                                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-8">
                                    @if($logo = (setting('logo_footer') ?: setting('logo_header')))
                                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ setting('app_name') }}" class="h-10 object-contain">
                                    @else
                                        <div class="flex items-center gap-2.5">
                                            @if(setting('favicon'))
                                                <div class="h-8 w-8 shrink-0">
                                                    <!-- SVG Abstract Icon (Replaces img for perfect transparency) -->
                                                    <svg viewBox="0 0 64 64" class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="32" cy="32" r="32" fill="#ec4899" />
                                                        <text x="32" y="36" font-family="sans-serif" font-size="34" font-weight="900" fill="white" text-anchor="middle" dominant-baseline="middle">a</text>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex items-baseline gap-1.5 transition-transform hover:scale-[1.02] duration-200">
                                                <span class="text-2xl font-black tracking-tight text-pink-500">{{ strtoupper(explode(' ', setting('app_name', 'AFILIA MARKET'))[0]) }}</span>
                                                <div class="relative">
                                                    <span class="text-2xl font-medium tracking-tight text-slate-800">{{ strtoupper(explode(' ', setting('app_name', 'AFILIA MARKET'))[1] ?? 'MARKET') }}</span>
                                                    <div class="absolute -bottom-1 left-0 w-full h-0.5 bg-pink-500 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </a>
                                <p class="text-slate-500 text-sm leading-relaxed max-w-sm mb-8">
                                    {{ setting('app_name') }} adalah platform e-commerce modern yang menyediakan berbagai kebutuhan gaya hidup Anda dengan kualitas terbaik dan pelayanan terpercaya.
                                </p>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-6">Bantuan</h4>
                                <ul class="space-y-4">
                                    <li><a href="{{ route('help') }}" wire:navigate class="text-[10px] font-bold text-gray-400 hover:text-indigo-600 transition-colors uppercase tracking-widest">Pusat Bantuan</a></li>
                                    <li><a href="{{ route('help') }}" wire:navigate class="text-[10px] font-bold text-gray-400 hover:text-indigo-600 transition-colors uppercase tracking-widest">Cara Belanja</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-6">Tentang</h4>
                                <ul class="space-y-4">
                                    <li><a href="{{ route('about') }}" wire:navigate class="text-[10px] font-bold text-gray-400 hover:text-indigo-600 transition-colors uppercase tracking-widest">Tentang Kami</a></li>
                                    <li><a href="{{ route('contact') }}" wire:navigate class="text-[10px] font-bold text-gray-400 hover:text-indigo-600 transition-colors uppercase tracking-widest">Kontak</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="py-8 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">
                                &copy; {{ setting('foundation_year', date('Y')) }} - {{ date('Y') }} {{ setting('company_official_name', setting('app_name')) }}.
                            </p>
                        </div>
                    </div>
                </footer>
                @endif

            </div>
            </div>

            <!-- Mobile Overlay (only when sidebar is open) -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-gray-900/10 sm:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
        </div>

        <x-toast />
        @livewireScripts
    </body>
</html>
