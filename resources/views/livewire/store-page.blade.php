<div class="py-16 bg-white min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-16 border-b border-gray-100 pb-8">
            <h1 class="text-3xl font-black text-gray-900 mb-4 uppercase tracking-tighter">Lokasi <span class="text-indigo-600">Toko Kami</span></h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">
                Kunjungi toko fisik kami untuk merasakan pengalaman belanja premium secara langsung.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($stores as $store)
            <div class="bg-white border border-gray-100 rounded-2xl p-8 hover:border-indigo-600 transition-colors group">
                <div class="mb-6 flex items-center justify-between">
                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1 rounded-full">{{ $store['city'] }}</span>
                    <svg class="w-5 h-5 text-gray-200 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                
                <h3 class="text-base font-bold text-gray-900 mb-4 tracking-tight leading-tight group-hover:text-indigo-600 transition-colors">
                    {{ $store['name'] }}
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 pt-1 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed font-medium">{{ $store['address'] }}</p>
                    </div>

                    <div class="flex items-start gap-4 pt-4 border-t border-gray-50">
                        <div class="shrink-0 pt-1 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                            {{ $store['hours'] }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-20 p-10 bg-gray-50 rounded-[2.5rem] border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-8">
            <div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Tidak menemukan toko di dekat Anda?</h4>
                <p class="text-xs text-gray-500 font-medium italic">Kami terus berkembang untuk menjangkau lebih banyak lokasi.</p>
            </div>
            <a href="{{ route('home') }}" wire:navigate class="px-8 py-4 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-800 transition-all flex items-center gap-3 group">
                Belanja Online Saja
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</div>
