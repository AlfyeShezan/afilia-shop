<div class="max-w-7xl mx-auto px-6 py-8 space-y-8 min-h-screen bg-gray-50">
    {{-- 1️⃣ HEADER --}}
    <div class="space-y-1">
        <p class="text-xs text-gray-400 uppercase tracking-widest">Admin / Manajemen</p>
        <h1 class="text-xl font-semibold text-gray-800">Manajemen Produk</h1>
        <p class="text-sm text-gray-600">Katalog produk dan manajemen inventaris platform.</p>
    </div>

    {{-- NOTIFICATION --}}
    @if (session()->has('message'))
        <div class="bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest p-4 rounded-md flex justify-between items-center animate-fade-in shadow-sm">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white">&times;</button>
        </div>
    @endif

    @if(!$isEditMode && !$productId)
    {{-- 2️⃣ TOOLBAR (LIST VIEW) --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau SKU..." 
                    class="w-full pl-10 border border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors">
            </div>
            <select wire:model.live="filterCategory" 
                class="w-full md:w-auto border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none bg-white font-medium text-gray-700">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="openModal" 
            class="bg-gray-900 text-white rounded-md px-4 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Produk
        </button>
    </div>

    {{-- 3️⃣ TABEL PRODUK (VERSI CLEAN & ENTERPRISE) --}}
    <div class="bg-white border border-gray-200 rounded-md overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Kategori</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-widest">Harga</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-widest">Stok</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr wire:key="product-{{ $product->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-md bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                    @php $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first(); @endphp
                                    @if($primaryImage)
                                        <img src="{{ Storage::url($primaryImage->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="1.5"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-tighter">SKU: {{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-gray-600 font-medium uppercase tracking-tight">{{ $product->category->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($product->sale_price)
                                <div class="text-xs text-gray-400 line-through mb-0.5">IDR {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="text-sm font-semibold text-gray-800">IDR {{ number_format($product->sale_price, 0, ',', '.') }}</div>
                            @else
                                <div class="text-sm font-semibold text-gray-800">IDR {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-medium {{ $product->stock < 5 ? 'text-gray-500' : 'text-gray-800' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border bg-gray-100 text-gray-700 border-gray-200">
                                {{ $product->status === 'active' ? 'Aktif' : ($product->status === 'draft' ? 'Draft' : 'Nonaktif') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <button wire:click="edit({{ $product->id }})" class="text-xs text-gray-600 hover:text-black font-medium transition-colors">Edit</button>
                                <button wire:click="confirmDelete({{ $product->id }})" class="text-xs text-red-600/70 hover:text-red-600 font-medium transition-colors">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm italic border-t border-gray-100">Katalog produk kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
            {{ $products->links() }}
        </div>
        @endif
    </div>
    @else
    {{-- 🧾 FORM TAMBAH / EDIT PRODUK (KONFIGURASI PRODUK BARU) --}}
    <div class="animate-slide-up space-y-8 pb-12">
        {{-- Section 1 — INFORMASI DASAR --}}
        <div class="bg-white border border-gray-200 rounded-md p-6 space-y-6 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-widest border-b border-gray-50 pb-3">Informasi Dasar</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Nama Produk</label>
                    <input type="text" wire:model.live="name" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors" placeholder="Contoh: Premium Leather Shoes">
                    @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Slug (Auto Readonly)</label>
                    <input type="text" wire:model="slug" class="w-full bg-gray-50 border border-gray-200 rounded-md px-4 py-2.5 text-sm font-mono text-gray-400" readonly>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Pilih Kategori</label>
                    <select wire:model="category_id" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm bg-white focus:ring-1 focus:ring-gray-400 focus:outline-none">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">SKU (Stock Keeping Unit)</label>
                    <input type="text" wire:model="sku" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none" placeholder="PROD-XXX">
                    @error('sku') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Section 2 — HARGA & INVENTARIS --}}
        <div class="bg-white border border-gray-200 rounded-md p-6 space-y-6 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-widest border-b border-gray-50 pb-3">Harga & Inventaris</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Harga Utama (IDR)</label>
                    <input type="number" step="0.01" wire:model="price" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                    @error('price') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Harga Diskon (IDR) - Opsional</label>
                    <input type="number" step="0.01" wire:model="sale_price" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Stok Tersedia</label>
                    <input type="number" wire:model="stock" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none">
                    @error('stock') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Status Publikasi</label>
                    <select wire:model="status" class="w-full border border-gray-300 rounded-md px-4 py-2.5 text-sm bg-white focus:ring-1 focus:ring-gray-400 focus:outline-none font-medium">
                        <option value="active">AKTIF & TERBIT</option>
                        <option value="draft">SIMPAN DRAFT</option>
                        <option value="inactive">NONAKTIF</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Section 3 — DESKRIPSI PRODUK --}}
        <div class="bg-white border border-gray-200 rounded-md p-6 space-y-6 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-widest border-b border-gray-50 pb-3">Deskripsi Produk</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Deskripsi Lengkap</label>
                    <textarea wire:model="description" class="w-full min-h-[160px] resize-none border border-gray-300 rounded-md px-4 py-2.5 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none" placeholder="Masukkan detail produk selengkapnya..."></textarea>
                </div>
            </div>
        </div>

        {{-- Section 4 — MANAJEMEN MEDIA --}}
        <div class="bg-white border border-gray-200 rounded-md p-6 space-y-6 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-widest border-b border-gray-50 pb-3">Manajemen Media</h3>
            <div class="space-y-6">
                {{-- UPLOAD AREA --}}
                <div class="relative">
                    <input type="file" wire:model="images" multiple id="product_images" class="hidden">
                    <label for="product_images" class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md p-10 cursor-pointer hover:bg-gray-50 transition-colors group">
                        <svg class="w-10 h-10 text-gray-300 group-hover:text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-sm text-gray-500 font-medium">Klik untuk unggah media baru</p>
                        <p class="text-xs text-gray-400 mt-1 uppercase tracking-tighter font-bold">Maksimal 5MB per gambar</p>
                    </label>
                    <div wire:loading wire:target="images" class="text-[10px] font-bold text-gray-900 mt-3 animate-pulse uppercase tracking-widest italic text-center">Sedang memproses unggahan...</div>
                    @error('images.*') <p class="text-red-600 text-[10px] mt-2 font-bold uppercase tracking-tight text-center">{{ $message }}</p> @enderror
                </div>

                {{-- PREVIEW GRID --}}
                @if($images || ($productId && $existingImages->isNotEmpty()))
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4 pt-6">
                    {{-- NEW IMAGES --}}
                    @foreach($images as $image)
                        <div class="aspect-square border border-gray-200 rounded-md overflow-hidden bg-gray-50 relative group">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-green-500/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-[8px] font-bold text-green-700 uppercase bg-white/90 px-1.5 py-0.5 rounded shadow-sm">Baru Diproses</span>
                            </div>
                        </div>
                    @endforeach

                    {{-- EXISTING IMAGES --}}
                    @if($productId)
                        @foreach($existingImages as $img)
                            <div class="relative aspect-square border-2 {{ $img->is_primary ? 'border-gray-900' : 'border-gray-100' }} rounded-md overflow-hidden bg-white group">
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                                
                                {{-- BADGE UTAMA --}}
                                @if($img->is_primary)
                                    <div class="absolute top-0 right-0 bg-gray-900 text-white text-[8px] font-bold px-2 py-1 tracking-tighter uppercase rounded-bl-sm z-10">Utama</div>
                                @endif

                                {{-- OVERLAY ACTIONS --}}
                                <div class="absolute inset-0 bg-white/80 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 p-3">
                                    @if(!$img->is_primary)
                                    <button type="button" wire:click="setPrimaryImage({{ $img->id }})" class="w-full py-1.5 bg-gray-900 text-white text-[9px] font-bold uppercase tracking-widest rounded-sm hover:bg-black transition-colors">Set Utama</button>
                                    @endif
                                    <button type="button" wire:click="deleteImage({{ $img->id }})" class="w-full py-1.5 bg-white border border-red-200 text-red-600 text-[9px] font-bold uppercase tracking-widest rounded-sm hover:bg-red-50 transition-colors">Hapus</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex items-center justify-end gap-6 pt-6 shrink-0">
            <button type="button" wire:click="closeModal" class="text-sm text-gray-500 hover:text-gray-900 font-medium transition-colors">
                Batalkan
            </button>
            <button type="button" wire:click="save" class="bg-gray-900 text-white rounded-md px-8 py-3 text-sm font-semibold hover:bg-gray-800 transition-all shadow-sm">
                Simpan Detail
            </button>
        </div>
    </div>
    @endif

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
                    <h3 class="text-base font-semibold text-gray-800 uppercase tracking-widest">Hapus Produk?</h3>
                    <p class="text-xs text-gray-500">Penghapusan produk akan menghapus semua aset media secara permanen.</p>
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
