<div class="max-w-5xl mx-auto px-6 py-8 space-y-8 min-h-screen bg-gray-50">
    {{-- 1️⃣ HEADER --}}
    <div class="flex justify-between items-center">
        <div class="space-y-1">
            <p class="text-xs text-gray-400 uppercase tracking-widest">Admin / Manajemen</p>
            <h1 class="text-xl font-semibold text-gray-800">Manajemen Kategori</h1>
            <p class="text-sm text-gray-600">Kelola struktur dan hierarki produk platform.</p>
        </div>
        <button wire:click="toggleForm" class="bg-gray-900 text-white rounded-md px-4 py-2.5 text-sm font-medium hover:bg-gray-800 transition shadow-sm">
            {{ $showForm ? 'Tutup Form' : 'Buat Kategori' }}
        </button>
    </div>

    {{-- NOTIFICATION --}}
    @if (session()->has('message'))
        <div class="bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest p-4 rounded-md flex justify-between items-center animate-fade-in shadow-sm">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white">&times;</button>
        </div>
    @endif

    {{-- 2️⃣ FORM SECTION (EXPANDABLE) --}}
    @if($showForm)
    <div class="animate-slide-up bg-white border border-gray-200 rounded-md p-6 space-y-6 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-widest border-b border-gray-50 pb-3">
            {{ $isEditMode ? 'Edit Kategori' : 'Buat Kategori Baru' }}
        </h3>
        
        <form wire:submit.prevent="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Nama Kategori</label>
                    <input type="text" wire:model.live="name" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors" placeholder="Contoh: Elektronik, Pakaian...">
                    @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Slug (Otomatis)</label>
                    <input type="text" wire:model="slug" class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-2.5 text-sm font-mono text-gray-400" readonly>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Kategori Induk</label>
                    <select wire:model="parent_id" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm bg-white focus:ring-1 focus:ring-gray-400 focus:outline-none">
                        <option value="">-- Tanpa Induk --</option>
                        @foreach($parentCategories as $cat)
                            @if(!$isEditMode || $cat->id != $categoryId)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('parent_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Status Aktif</label>
                    <select wire:model="is_active" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm bg-white focus:ring-1 focus:ring-gray-400 focus:outline-none">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4 shrink-0">
                <button type="button" wire:click="resetInput" class="text-sm text-gray-500 hover:text-gray-900 font-medium transition-colors">
                    Batalkan
                </button>
                <button type="submit" class="bg-gray-900 text-white rounded-md px-5 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors shadow-sm">
                    {{ $isEditMode ? 'Simpan Perubahan' : 'Simpan Kategori' }}
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- 3️⃣ LIST KATEGORI (VERSI CLEAN) --}}
    <div class="bg-white border border-gray-200 rounded-md overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Nama Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Induk</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orderedCategories as $category)
                    <tr wire:key="category-{{ $category->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($category->depth > 0)
                                    <span class="text-gray-300 mr-2" style="margin-left: {{ ($category->depth * 1) }}rem;">—</span>
                                @endif
                                <span class="font-medium {{ $category->depth > 0 ? 'text-gray-700' : 'text-gray-800' }}">
                                    {{ $category->name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($category->parent)
                                <span class="text-xs text-gray-600">{{ $category->parent->name }}</span>
                            @else
                                <span class="text-xs text-gray-400 italic">Top Level</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border
                                {{ $category->is_active ? 'bg-gray-100 text-gray-700 border-gray-200' : 'bg-gray-50 text-gray-400 border-gray-100' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <button wire:click="edit({{ $category->id }})" class="text-xs text-gray-600 hover:text-black font-medium transition-colors">Edit</button>
                                <button wire:click="confirmDelete({{ $category->id }})" class="text-xs text-red-600/70 hover:text-red-600 font-medium transition-colors">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm italic">Belum ada kategori ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- DELETE CONFIRMATION --}}
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-[2px] transition-opacity" wire:click="$set('confirmingDeletion', null)"></div>
        <div class="bg-white rounded-md border border-gray-200 relative w-full max-w-sm overflow-hidden animate-slide-up shadow-xl">
            <div class="p-8 text-center space-y-4">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-50 text-red-600 border border-red-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-semibold text-gray-800 uppercase tracking-widest">Hapus Kategori?</h3>
                    <p class="text-xs text-gray-500">Menghapus kategori akan memengaruhi struktur hierarki produk terkait.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-6">
                    <button wire:click="delete" class="bg-red-600 text-white rounded-md py-3 text-sm font-bold uppercase tracking-widest hover:bg-red-700 transition-colors">Hapus</button>
                    <button wire:click="$set('confirmingDeletion', null)" class="bg-white border border-gray-300 text-gray-600 rounded-md py-3 text-sm font-bold uppercase tracking-widest hover:bg-gray-50 transition-colors">Batal</button>
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
