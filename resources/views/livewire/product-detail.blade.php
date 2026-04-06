<div class="py-12 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Product Gallery -->
            <div class="w-full lg:w-1/2 space-y-4">
                <div class="aspect-video bg-gray-50 rounded-sm relative overflow-hidden border border-gray-100 flex items-center justify-center p-4">
                    @php
                        $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                    @endphp
                    @if($primaryImage)
                        <img src="{{ Storage::url($primaryImage->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-contain">
                    @endif
                    
                    @if($product->sale_price)
                    <div class="absolute top-4 left-4">
                        <span class="bg-gray-900 text-white text-[10px] font-bold px-3 py-1 rounded-sm uppercase tracking-widest">Sale</span>
                    </div>
                    @endif
                </div>

                @if($product->images->count() > 1)
                <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
                    @foreach($product->images as $image)
                    <button class="w-16 h-16 rounded-sm overflow-hidden border transition-all {{ $image->is_primary ? 'border-gray-900' : 'border-gray-100' }} bg-white flex-shrink-0 p-1">
                        <img src="{{ Storage::url($image->image_path) }}" class="w-full h-full object-contain">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="w-full lg:w-1/2">
                <div class="flex items-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">
                    <a href="{{ route('home') }}" wire:navigate class="hover:underline">Home</a>
                    <span>/</span>
                    <span class="text-gray-900">{{ $product->category->name }}</span>
                </div>

                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight mb-4">
                    {{ $product->name }}
                </h1>

                <div class="flex flex-col mb-8">
                    @if($product->sale_price)
                        <div class="flex items-center gap-4">
                            <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                            <span class="text-sm text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </div>

                <div class="space-y-8">
                    <!-- Attributes -->
                    @if(isset($product->metadata['attributes']))
                        <div class="space-y-6">
                            @if(!empty($product->metadata['attributes']['colors']))
                            <div>
                                <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Warna: <span class="text-gray-900">{{ $selectedColor }}</span></h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($product->metadata['attributes']['colors'] as $color)
                                    <button 
                                        wire:click="$set('selectedColor', '{{ $color['name'] }}')"
                                        class="w-8 h-8 rounded-sm border {{ $selectedColor == $color['name'] ? 'border-gray-900 ring-1 ring-gray-900 ring-offset-2' : 'border-gray-100 hover:border-gray-400' }} transition-all"
                                        style="background-color: {{ $color['code'] }}"
                                        title="{{ $color['name'] }}"
                                    ></button>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if(!empty($product->metadata['attributes']['sizes']))
                            <div>
                                <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Ukuran: <span class="text-gray-900">{{ $selectedSize }}</span></h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($product->metadata['attributes']['sizes'] as $size)
                                    <button 
                                        wire:click="$set('selectedSize', '{{ $size }}')"
                                        class="min-w-[48px] h-10 flex items-center justify-center rounded-sm border text-[10px] font-bold uppercase tracking-widest {{ $selectedSize == $size ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-200 text-gray-400 hover:border-gray-900 hover:text-gray-900' }} transition-all"
                                    >
                                        {{ $size }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif

                    <!-- Purchase Section -->
                    <div class="py-6 border-y border-gray-100 space-y-6">
                        <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest text-gray-400">
                            <span>Kuantitas</span>
                            <span>{{ $product->stock }} Tersedia</span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white transition focus-within:ring-2 focus-within:ring-gray-300 focus-within:border-gray-400">
                                <button wire:click="$set('quantity', {{ max(1, $quantity - 1) }})" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">-</button>
                                <input type="number" wire:model="quantity" class="w-12 border-x border-gray-300 border-y-0 text-center focus:ring-0 text-xs font-bold text-gray-900 h-10 flex items-center justify-center" readonly>
                                <button wire:click="$set('quantity', {{ min($product->stock, $quantity + 1) }})" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">+</button>
                            </div>
                            
                            <div class="flex flex-1 gap-2">
                                <button wire:click="buyNow" 
                                    class="flex-1 bg-gray-900 text-white h-12 rounded-sm text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all disabled:bg-gray-100 disabled:text-gray-400"
                                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    Beli Sekarang
                                </button>
                                
                                <button wire:click="addToCart" 
                                    class="flex-1 bg-white border border-gray-900 text-gray-900 h-12 rounded-sm text-[10px] font-bold uppercase tracking-widest hover:bg-gray-50 transition-all disabled:bg-gray-50 disabled:text-gray-300"
                                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    + Keranjang
                                </button>
                            </div>

                            <button wire:click.prevent="toggleWishlist({{ $product->id }})" class="w-12 h-12 flex items-center justify-center border border-gray-300 rounded-md hover:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition-all">
                                <svg class="w-5 h-5 {{ $this->isInWishlist($product->id) ? 'fill-gray-900 stroke-gray-900' : 'fill-none stroke-gray-400' }} transition-colors" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-4">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-900 pb-2 border-b border-gray-100">Informasi Produk</h3>
                        <div class="text-xs text-gray-500 leading-relaxed">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-24 pt-12 border-t border-gray-100">
            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-8 pb-4 border-b border-gray-100">Produk Serupa</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                    @include('livewire.partials.product-card-minimalist', ['product' => $related])
                @endforeach
            </div>
        </div>

        <livewire:recently-viewed-products />

        <div class="mt-24 pt-12 border-t border-gray-100">
            <livewire:product-review-section :product="$product" />
        </div>
    </div>
</div>
