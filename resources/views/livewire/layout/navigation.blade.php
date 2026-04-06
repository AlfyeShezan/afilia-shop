<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

new class extends Component
{
    public $cartCount = 0;
    public $wishlistCount = 0;

    public function mount()
    {
        $this->updateCartCount();
        $this->updateWishlistCount();
    }

    #[On('cart-updated')]
    public function updateCartCount()
    {
        if (Auth::check()) {
            $this->cartCount = CartItem::where('user_id', Auth::id())->sum('quantity') ?: 0;
        } else {
            $cart = session()->get('cart', []);
            $this->cartCount = array_sum(array_column($cart, 'quantity'));
        }
    }

    #[On('wishlist-updated')]
    public function updateWishlistCount()
    {
        if (Auth::check()) {
            $this->wishlistCount = \App\Models\Wishlist::where('user_id', Auth::id())->count();
        } else {
            $this->wishlistCount = 0;
        }
    }

}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left: Logo & Menu -->
            <div class="flex items-center gap-10">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3 group">
                    {{-- Dynamic Logo Branding --}}
                    @if(setting('logo_header'))
                        <img src="{{ asset('storage/' . setting('logo_header')) }}" alt="{{ setting('app_name') }}" class="h-10 object-contain">
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
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-1.5 transition-transform group-hover:scale-[1.02] duration-200">
                                <span class="text-xl font-black tracking-tight text-pink-500">{{ strtoupper(explode(' ', setting('app_name', 'AFILIA MARKET'))[0]) }}</span>
                                <div class="relative">
                                    <span class="text-xl font-medium tracking-tight text-slate-800">{{ strtoupper(explode(' ', setting('app_name', 'AFILIA MARKET'))[1] ?? 'MARKET') }}</span>
                                    <div class="absolute -bottom-1 left-0 w-full h-0.5 bg-pink-500 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </a>
                
                <div class="hidden sm:flex items-center gap-8">
                    <a href="{{ route('home') }}" wire:navigate class="text-xs font-bold uppercase tracking-widest {{ request()->routeIs('home') ? 'text-gray-900' : 'text-gray-400 hover:text-gray-900' }} transition-colors">Home</a>
                    <a href="{{ route('home') }}" wire:navigate class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-colors">Katalog</a>
                </div>
            </div>

            <!-- Center: Search (Minimalist) -->
            <div class="hidden md:block flex-1 max-w-md mx-10">
                <livewire:search />
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-6">
                <!-- Notifications -->
                @auth
                    <livewire:notifications />
                @endauth

                <!-- Wishlist -->
                <a href="{{ route('wishlist') }}" wire:navigate class="relative text-gray-400 hover:text-gray-900 transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    @if($wishlistCount > 0)
                        <span class="absolute -top-1 -right-1 bg-gray-900 text-white text-[8px] font-bold px-1 rounded-full min-w-[14px] h-3.5 flex items-center justify-center">{{ $wishlistCount }}</span>
                    @endif
                </a>

                <!-- Cart -->
                <a href="{{ route('cart') }}" wire:navigate class="relative text-gray-400 hover:text-gray-900 transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-gray-900 text-white text-[8px] font-bold px-1 rounded-full min-w-[14px] h-3.5 flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    <!-- Account Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-gray-400 hover:text-gray-900 transition-colors p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-4 border-b border-gray-50">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Signed in as</p>
                                <p class="text-xs font-bold text-gray-900 mt-0.5 truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <x-dropdown-link :href="route('profile')" wire:navigate>Profil</x-dropdown-link>
                            <x-dropdown-link :href="route('order.history')" wire:navigate>Pesanan Saya</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-gray-50">Keluar</button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-[10px] font-bold uppercase tracking-widest text-gray-900 hover:underline">Masuk</a>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="sm:hidden p-1 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-collapse class="sm:hidden bg-gray-50 border-t border-gray-100">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block text-xs font-bold uppercase tracking-widest text-gray-900">Home</a>
            <a href="{{ route('home') }}" class="block text-xs font-bold uppercase tracking-widest text-gray-900">Katalog</a>
            @auth
                <div class="pt-4 border-t border-gray-200">
                    <a href="{{ route('profile') }}" class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-3">Profil</a>
                    <a href="{{ route('order.history') }}" class="block text-xs font-bold uppercase tracking-widest text-gray-600 mb-3">Pesanan</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-bold uppercase tracking-widest text-red-600">Keluar</button>
                    </form>
                </div>
            @else
                <div class="pt-4 border-t border-gray-200">
                    <a href="{{ route('login') }}" class="block text-xs font-bold uppercase tracking-widest text-gray-900">Masuk</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
