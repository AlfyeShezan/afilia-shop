<div class="max-w-7xl mx-auto px-6 py-8 space-y-8 min-h-screen bg-gray-50">
    {{-- 1️⃣ HEADER --}}
    <div class="space-y-1">
        <p class="text-xs text-gray-400 uppercase tracking-widest">Admin / Manajemen</p>
        <h1 class="text-xl font-semibold text-gray-800">Manajemen Voucher</h1>
        <p class="text-sm text-gray-600">Strategi diskon dan loyalitas pelanggan platform.</p>
    </div>

    {{-- NOTIFICATION --}}
    @if(session('success'))
        <div class="bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest p-4 rounded-md flex justify-between items-center animate-fade-in shadow-sm">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white">&times;</button>
        </div>
    @endif

    {{-- 2️⃣ FORM VOUCHER (CREATE / EDIT) --}}
    @if($showForm)
    <div class="animate-slide-up space-y-8 pb-12">
        <div class="bg-white border border-gray-200 rounded-md p-6 space-y-8 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-widest border-b border-gray-50 pb-3">
                {{ $isEditing ? 'Ubah Konfigurasi Voucher' : 'Konfigurasi Voucher Baru' }}
            </h3>
            
            <form wire:submit="save" class="space-y-8">
                {{-- Section 1: Dasar & Kode --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Kode Unik</label>
                        <div class="flex gap-2">
                            <input wire:model="code" type="text" placeholder="MISAL: LEBARAN24" 
                                class="flex-1 border border-gray-300 rounded-md px-4 py-2.5 text-sm font-semibold text-gray-900 uppercase tracking-widest focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors"
                                oninput="this.value = this.value.toUpperCase()">
                            <button type="button" wire:click="generateCode" title="Acak Kode"
                                class="px-3 border border-gray-300 rounded-md text-gray-400 hover:text-black hover:border-black transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            </button>
                        </div>
                        @error('code') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Nama Kampanye</label>
                        <input wire:model="name" type="text" placeholder="Diskon Awal Tahun" 
                            class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm font-medium focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors">
                        @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Section 2: Konfigurasi Diskon --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Tipe Diskon</label>
                        <select wire:model.live="type" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm bg-white focus:ring-1 focus:ring-gray-400 focus:outline-none">
                            <option value="fixed">Nominal (IDR)</option>
                            <option value="percentage">Persentase (%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Besar Diskon</label>
                        <input wire:model="value" type="number" step="any" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                        @error('value') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Min. Belanja (IDR)</label>
                        <input wire:model="min_spend" type="number" step="any" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                        @error('min_spend') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Section 3: Batasan & Kuota --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if($type === 'percentage')
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Max. Potongan (IDR)</label>
                        <input wire:model="max_discount" type="number" step="any" placeholder="Kosong = No Limit" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Total Kuota Global</label>
                        <input wire:model="usage_limit" type="number" placeholder="Kosong = No Limit" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Limit Per Pengguna</label>
                        <input wire:model="per_user_limit" type="number" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                    </div>
                </div>

                {{-- Section 4: Durasi & Status --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Berlaku Mulai</label>
                        <input wire:model="starts_at" type="datetime-local" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Kedaluwarsa</label>
                        <input wire:model="expires_at" type="datetime-local" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                        @error('expires_at') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-md border border-gray-200 h-[42px]">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Status Aktif</span>
                        <button type="button" wire:click="$toggle('is_active')"
                            class="relative inline-flex h-5 w-10 items-center rounded-full transition-colors {{ $is_active ? 'bg-gray-900' : 'bg-gray-300' }}">
                            <span class="inline-block h-3 w-3 transform rounded-full bg-white transition-all {{ $is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Deskripsi Informasi</label>
                    <textarea wire:model="description" rows="2" placeholder="Tulis rincian voucher untuk pelanggan..."
                        class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none resize-none transition-colors"></textarea>
                </div>

                <div class="flex items-center justify-end gap-6 pt-6 shrink-0">
                    <button type="button" wire:click="cancelForm" class="text-sm text-gray-500 hover:text-gray-900 font-medium transition-colors">
                        Batalkan
                    </button>
                    <button type="submit" class="bg-gray-900 text-white rounded-md px-8 py-3 text-sm font-semibold hover:bg-gray-800 transition-all shadow-sm">
                        {{ $isEditing ? 'Simpan Perubahan' : 'Confirm & Save' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- 3️⃣ TOOLBAR (LIST VIEW) --}}
    @if(!$showForm)
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kode atau nama..." 
                    class="w-full pl-10 border border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors">
            </div>
            <select wire:model.live="filterStatus" 
                class="w-full md:w-auto border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none bg-white font-medium text-gray-700 uppercase tracking-widest text-[10px]">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
                <option value="expired">Kedaluwarsa</option>
            </select>
        </div>
        <button wire:click="openCreateForm" 
            class="bg-gray-900 text-white rounded-md px-4 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Buat Voucher Baru
        </button>
    </div>

    {{-- 4️⃣ TABEL VOUCHER --}}
    <div class="bg-white border border-gray-200 rounded-md overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Voucher</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Diskon</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Penggunaan</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($vouchers as $voucher)
                    <tr wire:key="voucher-{{ $voucher->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-mono font-bold text-blue-600 tracking-widest">{{ $voucher->code }}</div>
                                <div class="text-[11px] text-gray-500 uppercase font-medium mt-0.5">{{ $voucher->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">
                                @if($voucher->type === 'percentage')
                                    {{ number_format($voucher->value, 0) }}% <span class="text-[10px] text-gray-400">OFF</span>
                                @else
                                    Rp{{ number_format($voucher->value, 0, ',', '.') }}
                                @endif
                            </div>
                            <div class="text-[10px] text-gray-400 font-medium mt-0.5">Min. Rp{{ number_format($voucher->min_spend, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="font-medium text-gray-700 min-w-[3rem]">{{ $voucher->usage_count }} <span class="text-gray-300">/</span> {{ $voucher->usage_limit ?? '∞' }}</span>
                                <div class="w-20 h-1 bg-gray-100 rounded-full overflow-hidden shrink-0">
                                    @php 
                                        $percent = $voucher->usage_limit ? ($voucher->usage_count / $voucher->usage_limit) * 100 : 0; 
                                    @endphp
                                    <div class="h-full bg-gray-400 transition-all duration-500" style="width: {{ min($percent, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $status = $voucher->status_label;
                                $badgeClass = match($status) {
                                    'Aktif' => 'bg-gray-100 text-gray-700 border-gray-200',
                                    'Nonaktif' => 'bg-red-50 text-red-600 border-red-100',
                                    'Kedaluwarsa' => 'bg-gray-50 text-gray-400 border-gray-100',
                                    'Habis' => 'bg-gray-100 text-gray-500 border-gray-200',
                                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight border {{ $badgeClass }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <button wire:click="openEditForm({{ $voucher->id }})" class="text-xs text-gray-600 hover:text-black font-medium transition-colors">Edit</button>
                                <button wire:click="confirmDelete({{ $voucher->id }})" class="text-xs text-red-600/70 hover:text-red-600 font-medium transition-colors">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm italic">Belum ada voucher ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vouchers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
            {{ $vouchers->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- DELETE CONFIRMATION --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-[2px] transition-opacity" wire:click="$set('showDeleteModal', false)"></div>
        <div class="bg-white rounded-md border border-gray-200 relative w-full max-w-sm overflow-hidden animate-slide-up shadow-xl">
            <div class="p-8 text-center space-y-4">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-50 text-red-600 border border-red-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-semibold text-gray-800 uppercase tracking-widest">Hapus Voucher?</h3>
                    <p class="text-xs text-gray-500">Voucher yang dihapus tidak dapat dipulihkan oleh sistem.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-6">
                    <button wire:click="delete" class="bg-red-600 text-white rounded-md py-3 text-sm font-bold uppercase tracking-widest hover:bg-red-700 transition-colors">Hapus</button>
                    <button wire:click="$set('showDeleteModal', false)" class="bg-white border border-gray-300 text-gray-600 rounded-md py-3 text-sm font-bold uppercase tracking-widest hover:bg-gray-50 transition-colors">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes slide-up { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-in { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }
        .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    </style>
</div>
