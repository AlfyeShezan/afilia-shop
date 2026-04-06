<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-white min-h-screen">
    <div class="mb-12 border-b border-gray-100 pb-8 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900 uppercase tracking-widest">Alamat Pengiriman</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Kelola daftar alamat pengiriman Anda</p>
        </div>
        <button wire:click="create" class="inline-flex items-center px-6 py-2 bg-gray-900 text-white rounded-sm text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all">
            Tambah Alamat
        </button>
    </div>

    @if($isEditMode)
    <div class="border border-gray-100 rounded-sm p-8 mb-12 bg-gray-50/50">
        <h2 class="text-xs font-bold text-gray-900 uppercase tracking-widest mb-8">{{ $editingId ? 'Edit Alamat' : 'Tambah Alamat Baru' }}</h2>
        
        <form wire:submit.prevent="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-input-label for="label" class="text-[10px] uppercase font-bold tracking-widest text-gray-500">Label Alamat <span class="text-red-500">*</span></x-input-label>
                    <input wire:model="label" id="label" type="text" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition font-bold text-gray-900 shadow-none text-sm" placeholder="Rumah / Kantor / Dll" />
                    <x-input-error :messages="$errors->get('label')" class="mt-2 text-[10px] font-bold uppercase" />
                </div>
                <div class="space-y-2">
                    <x-input-label for="recipient_name" class="text-[10px] uppercase font-bold tracking-widest text-gray-500">Nama Penerima <span class="text-red-500">*</span></x-input-label>
                    <input wire:model="recipient_name" id="recipient_name" type="text" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition font-bold text-gray-900 shadow-none text-sm" />
                    <x-input-error :messages="$errors->get('recipient_name')" class="mt-2 text-[10px] font-bold uppercase" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-input-label for="phone_number" class="text-[10px] uppercase font-bold tracking-widest text-gray-500">Nomor Telepon <span class="text-red-500">*</span></x-input-label>
                    <input wire:model="phone_number" id="phone_number" type="text" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition font-bold text-gray-900 shadow-none text-sm" />
                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-[10px] font-bold uppercase" />
                </div>
                <div class="space-y-2">
                    <x-input-label for="city" class="text-[10px] uppercase font-bold tracking-widest text-gray-500">Kota / Kabupaten <span class="text-red-500">*</span></x-input-label>
                    <input wire:model="city" id="city" type="text" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition font-bold text-gray-900 shadow-none text-sm" />
                    <x-input-error :messages="$errors->get('city')" class="mt-2 text-[10px] font-bold uppercase" />
                </div>
            </div>

            <div class="space-y-2">
                <x-input-label for="full_address" class="text-[10px] uppercase font-bold tracking-widest text-gray-500">Alamat Lengkap <span class="text-red-500">*</span></x-input-label>
                <textarea wire:model="full_address" id="full_address" rows="3" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition font-bold text-gray-900 shadow-none text-sm"></textarea>
                <x-input-error :messages="$errors->get('full_address')" class="mt-2 text-[10px] font-bold uppercase" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-input-label for="state" class="text-[10px] uppercase font-bold tracking-widest text-gray-500">Provinsi <span class="text-red-500">*</span></x-input-label>
                    <input wire:model="state" id="state" type="text" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition font-bold text-gray-900 shadow-none text-sm" />
                    <x-input-error :messages="$errors->get('state')" class="mt-2 text-[10px] font-bold uppercase" />
                </div>
                <div class="space-y-2">
                    <x-input-label for="postal_code" class="text-[10px] uppercase font-bold tracking-widest text-gray-500">Kode Pos <span class="text-red-500">*</span></x-input-label>
                    <input wire:model="postal_code" id="postal_code" type="text" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition font-bold text-gray-900 shadow-none text-sm" />
                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2 text-[10px] font-bold uppercase" />
                </div>
            </div>

            <div class="flex items-center">
                <input wire:model="is_default" id="is_default" type="checkbox" class="w-4 h-4 text-gray-900 border-gray-300 rounded-sm focus:ring-0">
                <x-input-label for="is_default" value="Jadikan Alamat Utama" class="ml-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest" />
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                <button type="button" wire:click="resetForm" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-gray-900 transition-colors">Batal</button>
                <button type="submit" class="bg-gray-900 text-white px-8 py-2 rounded-sm text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all shadow-none">
                    Simpan Alamat
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="grid grid-cols-1 gap-4">
        @forelse($addresses as $address)
        <div class="border {{ $address->is_default ? 'border-gray-900 bg-gray-50/30' : 'border-gray-100' }} rounded-sm p-6 flex flex-col md:flex-row justify-between gap-6 transition-all group">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 bg-gray-50 border border-gray-200 rounded-sm text-[8px] font-bold uppercase tracking-widest text-gray-500">{{ $address->label }}</span>
                    @if($address->is_default)
                    <span class="px-2 py-0.5 bg-gray-900 rounded-sm text-[8px] font-bold uppercase tracking-widest text-white">Utama</span>
                    @endif
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ $address->recipient_name }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $address->phone_number }}</p>
                </div>
                <p class="text-[11px] text-gray-600 leading-relaxed font-bold">{{ $address->full_address }}</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
            </div>
            
            <div class="flex flex-row md:flex-col justify-end items-end gap-3">
                <div class="flex items-center gap-2">
                    <button wire:click="edit({{ $address->id }})" class="p-1.5 text-gray-400 hover:text-gray-900 border border-transparent hover:border-gray-200 rounded-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button wire:click="delete({{ $address->id }})" wire:confirm="Hapus alamat ini?" class="p-1.5 text-gray-400 hover:text-red-600 border border-transparent hover:border-gray-200 rounded-sm transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                @if(!$address->is_default)
                <button wire:click="setDefault({{ $address->id }})" class="text-[9px] font-bold uppercase tracking-widest text-gray-900 hover:underline transition-all">Set Utama</button>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-20 border border-dashed border-gray-200 rounded-sm">
            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-widest mb-2">Belum ada alamat</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-8">Tambahkan alamat untuk checkout lebih cepat</p>
            <button wire:click="create" class="inline-flex items-center px-8 py-3 bg-gray-900 text-white rounded-sm text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all">Tambah Alamat</button>
        </div>
        @endforelse
    </div>
</div>
