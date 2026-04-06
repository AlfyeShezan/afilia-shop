<div class="py-12 bg-white min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12 border-b border-gray-100 pb-8">
            <h1 class="text-xl font-bold text-gray-900 mb-2 uppercase tracking-widest">Pusat Bantuan</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Temukan jawaban atas pertanyaan Anda di sini</p>
        </div>

        {{-- Contact Support Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-12">
            <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center justify-between p-6 border border-gray-300 rounded-md hover:border-gray-900 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-md border border-gray-100 group-hover:bg-white transition-colors">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">WhatsApp</p>
                        <p class="text-[11px] font-bold text-gray-900 uppercase tracking-tight">Chat Dengan Kami</p>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-900 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="mailto:support@afilia.id" class="flex items-center justify-between p-6 border border-gray-300 rounded-md hover:border-gray-900 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-md border border-gray-100 group-hover:bg-white transition-colors">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Email</p>
                        <p class="text-[11px] font-bold text-gray-900 uppercase tracking-tight">Kirim Pesan</p>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-900 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- FAQ --}}
        <div x-data="{ open: null }" class="space-y-3">
            <h2 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-6 pb-4 border-b border-gray-50">Pertanyaan Umum</h2>

            @foreach($faqs as $i => $faq)
            <div class="border border-gray-300 rounded-md overflow-hidden bg-white transition-all hover:bg-gray-50/30">
                <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                    class="w-full flex items-center justify-between p-5 text-left focus:outline-none group">
                    <span class="text-[11px] font-bold text-gray-900 uppercase tracking-tight group-hover:text-gray-600 transition-colors">{{ $faq['q'] }}</span>
                    <svg :class="open === {{ $i }} ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $i }}" x-collapse class="px-5 pb-5">
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 leading-relaxed font-medium whitespace-pre-line">{!! nl2br(e($faq['a'])) !!}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
