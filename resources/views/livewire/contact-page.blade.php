<div class="py-12 md:py-16 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-6 space-y-10">
        {{-- SECTION 1 — HEADER --}}
        <div class="text-center space-y-3">
            <h1 class="text-xl font-semibold text-gray-800">Hubungi Kami</h1>
            <p class="text-sm text-gray-600 max-w-xl mx-auto">
                {{ setting('contact_description', 'Punya pertanyaan atau masukan? Tim kami siap membantu Anda.') }}
            </p>
        </div>

        {{-- SUCCESS NOTIFICATION --}}
        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-md px-4 py-3 text-sm flex items-center gap-3">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- SECTION 2 — INFORMASI KONTAK (CARD MINIMALIS) --}}
        <div class="bg-white border border-gray-200 rounded-md p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs uppercase text-gray-500 tracking-wider mb-1">Email</p>
                    <p class="text-sm text-gray-800 font-medium">{{ setting('support_email', 'support@afilia.id') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-gray-500 tracking-wider mb-1">WhatsApp</p>
                    <p class="text-sm text-gray-800 font-medium">{{ setting('whatsapp', '+62 812 3456 7890') }}</p>
                </div>
                <div class="md:col-span-2 pt-2 border-t border-gray-50">
                    <p class="text-xs uppercase text-gray-500 tracking-wider mb-1">Alamat Kantor</p>
                    <p class="text-sm text-gray-800 font-medium leading-relaxed">
                        {{ setting('address', 'SCBD District 8, Jakarta Selatan, Indonesia') }}
                        @if(setting('city')) , {{ setting('city') }} @endif
                        @if(setting('province')) , {{ setting('province') }} @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200"></div>

        {{-- SECTION 3 — FORM KONTAK --}}
        <div class="bg-white border border-gray-200 rounded-md p-6 sm:p-8">
            <form wire:submit.prevent="submit" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Nama Lengkap</label>
                        <input type="text" wire:model="name" 
                            class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-colors placeholder:text-gray-400" 
                            placeholder="Contoh: John Doe">
                        @error('name') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Email</label>
                        <input type="email" wire:model="email" 
                            class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-colors placeholder:text-gray-400" 
                            placeholder="john@example.com">
                        @error('email') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Subjek</label>
                    <select wire:model="subject" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-colors bg-white">
                        <option value="">Pilih Subjek</option>
                        <option value="pertanyaan">Pertanyaan Umum</option>
                        <option value="pesanan">Masalah Pesanan</option>
                        <option value="kemitraan">Kemitraan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    @error('subject') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1.5">Pesan</label>
                    <textarea wire:model="message" rows="5" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-colors placeholder:text-gray-400 resize-none" 
                        placeholder="Tuliskan pesan Anda selengkap mungkin..."></textarea>
                    @error('message') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" wire:loading.attr="disabled" 
                        class="w-full bg-gray-900 text-white rounded-md py-3 text-sm font-medium hover:bg-gray-800 transition-all focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 flex justify-center items-center gap-2">
                        <span wire:loading.remove>Kirim Pesan</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Mengirim...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
