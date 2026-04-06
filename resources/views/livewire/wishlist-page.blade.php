<div class="py-12 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12 border-b border-gray-100 pb-8">
            <h1 class="text-xl font-bold text-gray-900 mb-2 uppercase tracking-widest">Wishlist</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Daftar produk yang Anda simpan</p>
        </div>

        @if($wishlistItems->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($wishlistItems as $item)
                    @php $product = $item->product; @endphp
                    <div class="group relative bg-white border border-gray-300 rounded-md overflow-hidden hover:border-gray-900 transition-all p-4 flex flex-col">
                        <a href="{{ route('product.detail', $product->slug) }}" wire:navigate class="block aspect-video bg-gray-50 mb-4 relative rounded-sm overflow-hidden border border-gray-100 flex items-center justify-center p-2">
                            @if($product->images->count() > 0)
                                <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-contain duration-500">
                            @endif
                            
                            <!-- Remove Button -->
                            <button 
                                wire:click.stop="removeFromWishlist({{ $product->id }})" 
                                class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center bg-white/80 backdrop-blur-sm rounded-full border border-gray-100 shadow-sm hover:bg-white text-red-500 transition-all z-10"
                                title="Hapus dari Wishlist"
                            >
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </a>

                        <div class="flex flex-col flex-1">
                            <h3 class="text-[11px] font-bold text-gray-900 uppercase tracking-tight line-clamp-2 leading-tight group-hover:underline mb-3">{{ $product->name }}</h3>
                            
                            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-900">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                <button wire:click.prevent="traitAddToCart({{ $product->id }})" 
                                    class="text-[10px] font-bold text-gray-900 uppercase tracking-widest hover:text-indigo-600 transition-colors">
                                    + Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-24 text-center border border-gray-100 rounded-sm">
                <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest mb-8">Wishlist Anda Kosong</p>
                <a href="{{ route('home') }}" wire:navigate class="inline-block bg-gray-900 text-white px-8 py-3 rounded-sm text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>
