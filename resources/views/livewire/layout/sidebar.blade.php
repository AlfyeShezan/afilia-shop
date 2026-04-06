<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    //
}; ?>

<aside class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-4 py-8 overflow-y-auto bg-white border-r border-gray-200">
        {{-- Logo/Brand --}}
        <div class="flex items-center mb-10 px-2 transition-transform hover:scale-[1.02] duration-200">
            <a href="{{ route('home') }}" class="flex items-center" wire:navigate>
                @if(setting('logo_header'))
                    <img src="{{ asset('storage/' . setting('logo_header')) }}" alt="{{ setting('app_name') }}" class="h-8 object-contain">
                @else
                    <div class="flex items-center gap-2.5">
                        @if(setting('favicon'))
                            <div class="w-7 h-7 rounded-full bg-pink-500 flex items-center justify-center shrink-0">
                                <span class="text-white font-black text-sm leading-none mt-0.5">a</span>
                            </div>
                        @endif
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-black tracking-tight text-pink-500">{{ strtoupper(explode(' ', setting('app_name', 'AFILIA MARKET'))[0]) }}</span>
                            <div class="relative">
                                <span class="text-lg font-medium tracking-tight text-slate-800">{{ strtoupper(explode(' ', setting('app_name', 'AFILIA MARKET'))[1] ?? 'MARKET') }}</span>
                                <div class="absolute -bottom-0.5 left-0 w-full h-0.5 bg-pink-500 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </a>
        </div>

        <nav class="space-y-6">
            {{-- Main / Dashboard --}}
            <div>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-2.5 rounded-md transition-colors @if(request()->routeIs('admin.dashboard')) bg-gray-100 text-gray-900 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif"
                    wire:navigate>
                    <svg class="w-4 h-4 @if(request()->routeIs('admin.dashboard')) text-gray-900 @else text-gray-400 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>
            </div>

            {{-- Manajemen --}}
            @hasanyrole('super-admin|admin|staff')
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Manajemen</p>
                <div class="space-y-1 text-sm">
                    <a href="{{ route('admin.categories') }}"
                        class="flex items-center px-4 py-2 rounded-md transition-colors @if(request()->routeIs('admin.categories')) bg-gray-100 text-gray-900 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif"
                        wire:navigate>
                        <svg class="w-4 h-4 @if(request()->routeIs('admin.categories')) text-gray-900 @else text-gray-400 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="ml-3">Kategori</span>
                    </a>
                    <a href="{{ route('admin.products') }}"
                        class="flex items-center px-4 py-2 rounded-md transition-colors @if(request()->routeIs('admin.products')) bg-gray-100 text-gray-900 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif"
                        wire:navigate>
                        <svg class="w-4 h-4 @if(request()->routeIs('admin.products')) text-gray-900 @else text-gray-400 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="ml-3">Produk</span>
                    </a>
                    <a href="{{ route('admin.orders') }}"
                        class="flex items-center px-4 py-2 rounded-md transition-colors @if(request()->routeIs('admin.orders')) bg-gray-100 text-gray-900 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif"
                        wire:navigate>
                        <svg class="w-4 h-4 @if(request()->routeIs('admin.orders')) text-gray-900 @else text-gray-400 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="ml-3">Pesanan</span>
                    </a>
                    <a href="{{ route('admin.users') }}"
                        class="flex items-center px-4 py-2 rounded-md transition-colors @if(request()->routeIs('admin.users')) bg-gray-100 text-gray-900 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif"
                        wire:navigate>
                        <svg class="w-4 h-4 @if(request()->routeIs('admin.users')) text-gray-900 @else text-gray-400 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="ml-3">Pengguna</span>
                    </a>
                </div>
            </div>

            {{-- Marketing --}}
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Marketing</p>
                <div class="space-y-1 text-sm">
                    <a href="{{ route('admin.vouchers') }}"
                        class="flex items-center px-4 py-2 rounded-md transition-colors @if(request()->routeIs('admin.vouchers')) bg-gray-100 text-gray-900 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif"
                        wire:navigate>
                        <svg class="w-4 h-4 @if(request()->routeIs('admin.vouchers')) text-gray-900 @else text-gray-400 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4v-3a2 2 0 00-2-2H5z" />
                        </svg>
                        <span class="ml-3">Voucher</span>
                    </a>
                </div>
            </div>
            @endhasanyrole

            {{-- Sistem --}}
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Sistem</p>
                <div class="space-y-1 text-sm">
                    <a href="{{ route('admin.settings') }}"
                        class="flex items-center px-4 py-2 rounded-md transition-colors @if(request()->routeIs('admin.settings')) bg-gray-100 text-gray-900 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif"
                        wire:navigate>
                        <svg class="w-4 h-4 @if(request()->routeIs('admin.settings')) text-gray-900 @else text-gray-400 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="ml-3">Pengaturan Sistem</span>
                    </a>
                    <a href="{{ route('profile') }}"
                        class="flex items-center px-4 py-2 rounded-md transition-colors @if(request()->routeIs('profile')) bg-gray-100 text-gray-900 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif"
                        wire:navigate>
                        <svg class="w-4 h-4 @if(request()->routeIs('profile')) text-gray-900 @else text-gray-400 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="ml-3">Pengaturan Akun</span>
                    </a>
                </div>
            </div>

            {{-- Logout --}}
            <div class="pt-6 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-2 rounded-md text-gray-400 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="ml-3">Keluar</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>
</aside>
