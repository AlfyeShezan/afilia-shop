<div class="relative w-full" x-data="{ open: @entangle('showResults') }" @click.away="open = false">
    <div class="relative flex items-center">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="query"
            @focus="if($wire.query.length >= 2) open = true"
            placeholder="Cari produk..." 
            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition text-xs text-gray-900 placeholder:text-gray-400 bg-white"
        >
        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <div wire:loading wire:target="query" class="absolute right-10 top-1/2 -translate-y-1/2">
            <svg class="animate-spin h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <!-- Dropdown Results -->
    <div 
        x-show="open" 
        class="absolute top-full left-0 mt-1 w-full bg-white border border-gray-200 rounded-sm shadow-lg overflow-hidden z-50"
        style="display: none;"
    >
        @if(count($results) > 0 || count($categories) > 0)
            <div>
                @if(count($categories) > 0)
                    <div class="p-3 border-b border-gray-100">
                        <h4 class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-2 px-1">Kategori</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($categories as $category)
                                <a href="{{ route('home', ['selectedCategory' => $category->id]) }}" class="text-[10px] font-bold text-gray-600 hover:text-gray-900 transition-colors bg-gray-50 px-2 py-1 rounded-sm border border-gray-100">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(count($results) > 0)
                    <div class="py-2">
                        @foreach($results as $product)
                            <a href="{{ route('product.detail', $product->slug) }}" wire:navigate class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition-colors group">
                                <div class="w-8 h-8 bg-gray-50 overflow-hidden flex-shrink-0 border border-gray-100 rounded-sm">
                                    @if($product->primaryImage)
                                        <img src="{{ Storage::url($product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-[11px] font-bold text-gray-900 truncate group-hover:underline">{{ $product->name }}</h5>
                                    <p class="text-[10px] text-gray-400 font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                <a href="{{ route('home', ['search' => $query]) }}" class="block py-2 bg-gray-50 hover:bg-gray-100 text-center border-t border-gray-100">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-gray-900">Lihat Semua Hasil</span>
                </a>
            </div>
        @else
            <div class="p-6 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Tidak ditemukan
                </p>
            </div>
        @endif
    </div>
</div>
