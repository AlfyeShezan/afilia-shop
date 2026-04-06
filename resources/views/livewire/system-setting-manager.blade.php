<div class="p-6 bg-gray-50 min-h-screen space-y-8">
    {{-- HEADER --}}
    <div class="pb-6 border-b border-gray-200">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Admin / Sistem</p>
        <h1 class="text-xl font-semibold text-gray-800">Pengaturan Sistem</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola identitas dan branding platform</p>
    </div>

    {{-- NOTIFICATION --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm flex items-center gap-3">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- TABS NAVIGATION --}}
        <div class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
                <nav class="flex flex-col">
                    <button wire:click="setTab('branding')" 
                        class="px-5 py-3 text-left text-sm transition-colors border-l-2 {{ $activeTab === 'branding' ? 'bg-gray-50 border-gray-900 text-gray-900 font-medium' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                        Visual Branding
                    </button>
                    <button wire:click="setTab('contact')" 
                        class="px-5 py-3 text-left text-sm transition-colors border-l-2 {{ $activeTab === 'contact' ? 'bg-gray-50 border-gray-900 text-gray-900 font-medium' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                        Informasi Kontak
                    </button>
                    <button wire:click="setTab('social')" 
                        class="px-5 py-3 text-left text-sm transition-colors border-l-2 {{ $activeTab === 'social' ? 'bg-gray-50 border-gray-900 text-gray-900 font-medium' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                        Sosial Media
                    </button>
                    <button wire:click="setTab('legal')" 
                        class="px-5 py-3 text-left text-sm transition-colors border-l-2 {{ $activeTab === 'legal' ? 'bg-gray-50 border-gray-900 text-gray-900 font-medium' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                        Informasi Legal
                    </button>
                </nav>
            </div>
        </div>

        {{-- SETTINGS CONTENT --}}
        <div class="flex-grow">
            <form wire:submit.prevent="save" class="space-y-6">
                {{-- TAB: BRANDING --}}
                @if($activeTab === 'branding')
                    <div class="bg-white border border-gray-200 rounded-md p-6 space-y-8">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2 uppercase tracking-tight">Visual Branding</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Logo Header --}}
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Logo Utama (Header)</label>
                                    <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-md p-4 bg-gray-50">
                                        @if(isset($uploads['logo_header']))
                                            <img src="{{ $uploads['logo_header']->temporaryUrl() }}" class="max-h-20 mb-3">
                                        @elseif(!empty($settings['logo_header']['value']))
                                            <img src="{{ asset('storage/' . $settings['logo_header']['value']) }}" class="max-h-20 mb-3">
                                        @else
                                            <div class="text-gray-400 text-xs mb-3 text-center">Belum ada logo</div>
                                        @endif
                                        <div class="flex gap-2">
                                            <input type="file" wire:model="uploads.logo_header" class="hidden" id="logo_header">
                                            <label for="logo_header" class="cursor-pointer text-xs bg-white border border-gray-300 px-3 py-1.5 rounded text-gray-600 hover:bg-gray-50 transition-colors">
                                                Pilih Logo
                                            </label>
                                            @if(!empty($settings['logo_header']['value']) || isset($uploads['logo_header']))
                                                <button type="button" wire:click="removeFile('logo_header')" wire:confirm="Hapus logo ini?" class="text-xs bg-red-50 border border-red-200 px-3 py-1.5 rounded text-red-600 hover:bg-red-100 transition-colors">
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-400">Rekomendasi: PNG transparan, max 2MB</p>
                                </div>

                                {{-- Logo Verse Dark --}}
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Logo Versi Dark (Opsional)</label>
                                    <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-md p-4 bg-gray-900">
                                        @if(isset($uploads['logo_dark']))
                                            <img src="{{ $uploads['logo_dark']->temporaryUrl() }}" class="max-h-20 mb-3">
                                        @elseif(!empty($settings['logo_dark']['value']))
                                            <img src="{{ asset('storage/' . $settings['logo_dark']['value']) }}" class="max-h-20 mb-3">
                                        @else
                                            <div class="text-gray-500 text-xs mb-3 text-center">Belum ada logo</div>
                                        @endif
                                        <div class="flex gap-2">
                                            <input type="file" wire:model="uploads.logo_dark" class="hidden" id="logo_dark">
                                            <label for="logo_dark" class="cursor-pointer text-xs bg-gray-800 border border-gray-700 px-3 py-1.5 rounded text-gray-300 hover:bg-gray-700 transition-colors">
                                                Pilih Logo
                                            </label>
                                            @if(!empty($settings['logo_dark']['value']) || isset($uploads['logo_dark']))
                                                <button type="button" wire:click="removeFile('logo_dark')" wire:confirm="Hapus logo ini?" class="text-xs bg-red-900 border border-red-800 px-3 py-1.5 rounded text-red-100 hover:bg-red-800 transition-colors">
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-400">Untuk background gelap / footer</p>
                                </div>

                                {{-- Logo Footer --}}
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Logo Footer</label>
                                    <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-md p-4 bg-gray-50">
                                        @if(isset($uploads['logo_footer']))
                                            <img src="{{ $uploads['logo_footer']->temporaryUrl() }}" class="max-h-20 mb-3">
                                        @elseif(!empty($settings['logo_footer']['value']))
                                            <img src="{{ asset('storage/' . $settings['logo_footer']['value']) }}" class="max-h-20 mb-3">
                                        @else
                                            <div class="text-gray-400 text-xs mb-3 text-center">Belum ada logo</div>
                                        @endif
                                        <div class="flex gap-2">
                                            <input type="file" wire:model="uploads.logo_footer" class="hidden" id="logo_footer">
                                            <label for="logo_footer" class="cursor-pointer text-xs bg-white border border-gray-300 px-3 py-1.5 rounded text-gray-600 hover:bg-gray-50 transition-colors">
                                                Pilih Logo
                                            </label>
                                            @if(!empty($settings['logo_footer']['value']) || isset($uploads['logo_footer']))
                                                <button type="button" wire:click="removeFile('logo_footer')" wire:confirm="Hapus logo ini?" class="text-xs bg-red-50 border border-red-200 px-3 py-1.5 rounded text-red-600 hover:bg-red-100 transition-colors">
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Favicon --}}
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Favicon (32x32)</label>
                                    <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-md p-4 bg-gray-50">
                                        @if(isset($uploads['favicon']))
                                            <img src="{{ $uploads['favicon']->temporaryUrl() }}" class="w-8 h-8 mb-3">
                                        @elseif(!empty($settings['favicon']['value']))
                                            <img src="{{ asset('storage/' . $settings['favicon']['value']) }}" class="w-8 h-8 mb-3">
                                        @else
                                            <div class="text-gray-400 text-xs mb-3 text-center">Belum ada</div>
                                        @endif
                                        <div class="flex gap-2">
                                            <input type="file" wire:model="uploads.favicon" class="hidden" id="favicon">
                                            <label for="favicon" class="cursor-pointer text-xs bg-white border border-gray-300 px-3 py-1.5 rounded text-gray-600 hover:bg-gray-50 transition-colors">
                                                Pilih Icon
                                            </label>
                                            @if(!empty($settings['favicon']['value']) || isset($uploads['favicon']))
                                                <button type="button" wire:click="removeFile('favicon')" wire:confirm="Hapus ikon ini?" class="text-xs bg-red-50 border border-red-200 px-3 py-1.5 rounded text-red-600 hover:bg-red-100 transition-colors">
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- OG Image --}}
                                <div class="md:col-span-2 space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Default OG Image (Social Share)</label>
                                    <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-md p-6 bg-gray-50">
                                        @if(isset($uploads['og_image']))
                                            <img src="{{ $uploads['og_image']->temporaryUrl() }}" class="max-h-40 mb-3 rounded shadow-sm">
                                        @elseif(!empty($settings['og_image']['value']))
                                            <img src="{{ asset('storage/' . $settings['og_image']['value']) }}" class="max-h-40 mb-3 rounded shadow-sm">
                                        @else
                                            <div class="text-gray-400 text-xs mb-3 text-center">Belum ada gambar preview</div>
                                        @endif
                                        <div class="flex gap-2">
                                            <input type="file" wire:model="uploads.og_image" class="hidden" id="og_image">
                                            <label for="og_image" class="cursor-pointer text-xs bg-white border border-gray-300 px-3 py-1.5 rounded text-gray-600 hover:bg-gray-50 transition-colors">
                                                Pilih Gambar
                                            </label>
                                            @if(!empty($settings['og_image']['value']) || isset($uploads['og_image']))
                                                <button type="button" wire:click="removeFile('og_image')" wire:confirm="Hapus gambar ini?" class="text-xs bg-red-50 border border-red-200 px-3 py-1.5 rounded text-red-600 hover:bg-red-100 transition-colors">
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-400">Rasio 1200x630px untuk hasil terbaik di sosial media.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- TAB: CONTACT --}}
                @if($activeTab === 'contact')
                    <div class="bg-white border border-gray-200 rounded-md p-6 space-y-6">
                        <h3 class="text-sm font-semibold text-gray-800 border-b border-gray-100 pb-2 uppercase tracking-tight">Informasi Kontak</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach(['app_name', 'sys_email', 'support_email', 'whatsapp', 'phone'] as $key)
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">
                                        {{ str_replace('_', ' ', $key) }}
                                    </label>
                                    <input type="text" wire:model.defer="settings.{{ $key }}.value"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 placeholder-gray-400 transition-colors"
                                        placeholder="Masukkan {{ str_replace('_', ' ', $key) }}">
                                </div>
                            @endforeach

                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Alamat Lengkap</label>
                                <textarea wire:model.defer="settings.address.value" rows="3"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 placeholder-gray-400 transition-colors"
                                    placeholder="Alamat lengkap toko..."></textarea>
                            </div>

                            @foreach(['city', 'province', 'postal_code', 'country'] as $key)
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">{{ str_replace('_', ' ', $key) }}</label>
                                    <input type="text" wire:model.defer="settings.{{ $key }}.value"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 placeholder-gray-400 transition-colors">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- TAB: SOCIAL --}}
                @if($activeTab === 'social')
                    <div class="bg-white border border-gray-200 rounded-md p-6 space-y-6">
                        <h3 class="text-sm font-semibold text-gray-800 border-b border-gray-100 pb-2 uppercase tracking-tight">Sosial Media</h3>
                        
                        <div class="space-y-4">
                            @foreach(['instagram_url', 'facebook_url', 'tiktok_url', 'youtube_url', 'twitter_url'] as $key)
                                <div class="flex items-center gap-4">
                                    <div class="w-24 text-xs font-semibold text-gray-500 uppercase tracking-widest">{{ explode('_', $key)[0] }}</div>
                                    <input type="text" wire:model.defer="settings.{{ $key }}.value"
                                        class="flex-grow border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 placeholder-gray-400 transition-colors"
                                        placeholder="https://...">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- TAB: LEGAL --}}
                @if($activeTab === 'legal')
                    <div class="bg-white border border-gray-200 rounded-md p-6 space-y-6">
                        <h3 class="text-sm font-semibold text-gray-800 border-b border-gray-100 pb-2 uppercase tracking-tight">Informasi Legal</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Nama Perusahaan Resmi</label>
                                <input type="text" wire:model.defer="settings.company_official_name.value"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 placeholder-gray-400 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Tahun Berdiri</label>
                                <input type="text" wire:model.defer="settings.foundation_year.value"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 placeholder-gray-400 transition-colors">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1.5">Teks Hak Cipta Footer</label>
                                <textarea wire:model.defer="settings.copyright_text.value" rows="2"
                                    class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 placeholder-gray-400 transition-colors"></textarea>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- SAVE BUTTON --}}
                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-8 py-3 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 disabled:opacity-50 flex items-center gap-2"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Seluruh Perubahan</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
