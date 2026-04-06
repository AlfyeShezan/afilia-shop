<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-6 space-y-8" x-data="{ activeSection: null }">
            {{-- 1️⃣ HEADER --}}
            <div class="space-y-1">
                <h1 class="text-xl font-semibold text-gray-800">Pengaturan Akun</h1>
                <p class="text-sm text-gray-600">Kelola informasi dan keamanan akun Anda.</p>
            </div>

            {{-- 2️⃣ PROFIL RINGKAS --}}
            <div class="bg-white border border-gray-200 rounded-md p-6 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-600 border border-gray-100 uppercase">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="flex-grow">
                    <h2 class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</h2>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    <div class="mt-1">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-tight bg-gray-50 text-gray-400 border border-gray-100">
                            {{ auth()->user()->getRoleNames()->first() ?? 'Member' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- 3️⃣ SECTION SETTINGS (ACCORDION) --}}
            <div class="space-y-3">
                {{-- Edit Profil --}}
                <div class="space-y-3">
                    <button @click="activeSection = (activeSection === 'profile' ? null : 'profile')"
                        class="w-full text-left px-4 py-3 border border-gray-200 rounded-md text-sm transition-colors flex items-center justify-between shadow-sm"
                        :class="activeSection === 'profile' ? 'bg-gray-50 border-gray-300 font-medium text-gray-900' : 'bg-white text-gray-600 hover:bg-gray-50'">
                        <span>Edit Profil</span>
                        <svg class="w-4 h-4 transition-transform" :class="activeSection === 'profile' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeSection === 'profile'" x-collapse x-cloak>
                        <div class="mt-4 bg-white border border-gray-200 rounded-md p-6 shadow-sm">
                            <livewire:profile.update-profile-information-form />
                        </div>
                    </div>
                </div>

                {{-- Ubah Password --}}
                <div class="space-y-3">
                    <button @click="activeSection = (activeSection === 'password' ? null : 'password')"
                        class="w-full text-left px-4 py-3 border border-gray-200 rounded-md text-sm transition-colors flex items-center justify-between shadow-sm"
                        :class="activeSection === 'password' ? 'bg-gray-50 border-gray-300 font-medium text-gray-900' : 'bg-white text-gray-600 hover:bg-gray-50'">
                        <span>Ubah Password</span>
                        <svg class="w-4 h-4 transition-transform" :class="activeSection === 'password' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeSection === 'password'" x-collapse x-cloak>
                        <div class="mt-4 bg-white border border-gray-200 rounded-md p-6 shadow-sm">
                            <livewire:profile.update-password-form />
                        </div>
                    </div>
                </div>

                {{-- Keamanan Akun --}}
                <div class="space-y-3">
                    <button @click="activeSection = 'security'"
                        class="w-full text-left px-4 py-3 border border-gray-200 rounded-md text-sm transition-colors flex items-center justify-between shadow-sm"
                        :class="activeSection === 'security' ? 'bg-gray-50 border-gray-300 font-medium text-gray-900' : 'bg-white text-gray-600 hover:bg-gray-50'">
                        <span>Keamanan</span>
                        <svg class="w-4 h-4 transition-transform" :class="activeSection === 'security' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeSection === 'security'" x-collapse x-cloak>
                        <div class="mt-4 bg-white border border-gray-200 rounded-md p-6 shadow-sm space-y-6">
                            <div class="border-b border-gray-50 pb-4 flex justify-between items-center">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest">Informasi Sesi</h3>
                                <span class="bg-green-50 text-green-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-green-100 uppercase tracking-tighter">Verified Device</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Login Terakhir</p>
                                    <p class="text-sm text-gray-800 font-medium">{{ auth()->user()->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">IP Address</p>
                                    <p class="text-sm text-gray-800 font-medium">{{ request()->ip() }}</p>
                                </div>
                            </div>
                            <div class="pt-4">
                                <button class="text-xs text-gray-500 hover:text-black font-medium transition-colors">
                                    Logout dari semua perangkat?
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4️⃣ DANGER ZONE --}}
            <div class="pt-8 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Danger Zone</h3>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                    <button @click="$dispatch('open-modal', 'confirm-user-deletion')" 
                        class="text-sm text-gray-500 hover:text-gray-800 font-medium transition-colors">
                        Hapus Akun
                    </button>
                </div>
            </div>

            {{-- DELETE MODAL (Global placement) --}}
            <livewire:profile.delete-user-form />
            
            {{-- BACK BUTTON --}}
            <div class="text-center pt-8">
                <a href="{{ route('home') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-900 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        @keyframes slide-up { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }
    </style>
</x-app-layout>
