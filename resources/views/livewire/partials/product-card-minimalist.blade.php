<div class="group bg-white border border-gray-100 rounded-sm overflow-hidden flex flex-col transition-colors hover:border-gray-900">
    {{-- Image Area --}}
    <a href="{{ route('product.detail', $product->slug) }}" wire:navigate class="relative aspect-square overflow-hidden bg-gray-50 flex items-center justify-center">
        @php
            $cardImage = $product->primaryImage ?? ($product->images->first() ?? null);
        @endphp

        @if($cardImage)
            <img src="{{ Storage::url($cardImage->image_path) }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-contain p-2 duration-500">
        @else
            <div class="text-gray-200">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
        @endif

        {{-- Wishlist Button --}}
        <button 
            wire:click.stop.prevent="toggleWishlist({{ $product->id }})" 
            active:scale-110
            class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full border border-gray-100 shadow-sm hover:bg-white transition-all z-10">
            <svg class="w-4 h-4 {{ $this->isInWishlist($product->id) ? 'fill-gray-900 stroke-gray-900' : 'fill-none stroke-gray-400' }} transition-colors" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>
    </a>

    {{-- Details --}}
    <div class="p-3 flex flex-col flex-grow">
        <a href="{{ route('product.detail', $product->slug) }}" wire:navigate class="mb-2">
            <h3 class="text-[11px] font-bold text-gray-900 uppercase tracking-tight line-clamp-2 leading-tight group-hover:underline">{{ $product->name }}</h3>
        </a>

        <div class="mt-auto">
            <div class="flex items-center gap-2">
                @if($product->sale_price)
                    <span class="text-sm font-bold text-gray-900">Rp{{ number_format($product->sale_price, 0, ',', '.') }}</span>
                    <span class="text-[9px] text-gray-400 line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                @else
                    <span class="text-sm font-bold text-gray-900">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>
            
            <div class="mt-2 text-[9px] text-gray-400 font-bold uppercase tracking-widest flex items-center justify-between">
                <span>{{ $product->category->name }}</span>
            </div>
        </div>
    </div>
</div>
