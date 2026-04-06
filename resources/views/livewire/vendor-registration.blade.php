<div class="min-h-screen py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-12 text-center">
            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.3em] mb-4">Mulai Berjualan</p>
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter leading-none mb-6">MITRA AFILIA<br>MARKET</h1>
            <p class="text-slate-500 text-sm font-medium leading-relaxed mb-12">Bergabunglah dengan komunitas penjual Afilia terbesar dan mulai kembangkan bisnis Anda bersama kami.</p>
        </div>

        <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                <!-- Branding/Info Column -->
                <div class="lg:w-1/3 bg-indigo-600 p-12 text-white flex flex-col justify-between">
                    <div>
                        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-8">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h2 class="text-3xl font-black mb-6 leading-tight">Tumbuh Bersama Kami</h2>
                        <ul class="space-y-6">
                            <li class="flex gap-4">
                                <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-indigo-100">Komisi platform rendah 10%</span>
                            </li>
                            <li class="flex gap-4">
                                <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-indigo-100">Panel kontrol vendor canggih</span>
                            </li>
                            <li class="flex gap-4">
                                <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-indigo-100">Jangkau jutaan pelanggan</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-20">
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-60">Versi Sistem 2.0</p>
                    </div>
                </div>

                <!-- Form Column -->
                <div class="flex-1 p-12 lg:p-16">
                    <form wire:submit.prevent="registerVendor" class="space-y-10">
                        <!-- Shop Name -->
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Nama Toko Profesional</label>
                            <input wire:model="name" type="text" class="w-full h-14 bg-gray-50 border-none rounded-2xl px-6 font-bold text-gray-900 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-gray-300" placeholder="contoh: Urban Style Official">
                            @error('name') <span class="text-[10px] text-red-500 font-bold uppercase mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Kisah Merek / Deskripsi</label>
                            <textarea wire:model="description" rows="4" class="w-full bg-gray-50 border-none rounded-2xl p-6 font-bold text-gray-900 focus:ring-4 focus:ring-indigo-100 transition-all placeholder:text-gray-300" placeholder="Beritahu pelanggan tentang misi dan produk Anda..."></textarea>
                            @error('description') <span class="text-[10px] text-red-500 font-bold uppercase mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Brand Visuals -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Logo -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Logo Toko</label>
                                <div class="relative group">
                                    <label class="w-full h-32 bg-gray-50 border-2 border-dashed border-gray-100 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:bg-gray-100 hover:border-indigo-400 transition-all overflow-hidden">
                                        @if($logo)
                                            <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span class="text-[8px] font-black text-gray-400 uppercase">Rekomendasi Kotak</span>
                                        @endif
                                        <input type="file" wire:model="logo" class="hidden">
                                    </label>
                                </div>
                                @error('logo') <span class="text-[10px] text-red-500 font-bold uppercase mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Banner -->
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Banner Toko</label>
                                <div class="relative group">
                                    <label class="w-full h-32 bg-gray-50 border-2 border-dashed border-gray-100 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:bg-gray-100 hover:border-indigo-400 transition-all overflow-hidden">
                                        @if($banner)
                                            <img src="{{ $banner->temporaryUrl() }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            <span class="text-[8px] font-black text-gray-400 uppercase">Disarankan Lanskap</span>
                                        @endif
                                        <input type="file" wire:model="banner" class="hidden">
                                    </label>
                                </div>
                                @error('banner') <span class="text-[10px] text-red-500 font-bold uppercase mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="pt-6">
                            <button type="submit" class="w-full h-16 bg-indigo-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-indigo-100 hover:bg-gray-900 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                                <span>Luncurkan Toko Saya</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                            <p class="text-center text-[10px] font-black text-gray-400 uppercase tracking-widest mt-6">Dengan mengeklik, Anda menyetujui Syarat & Ketentuan Marketplace kami</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
