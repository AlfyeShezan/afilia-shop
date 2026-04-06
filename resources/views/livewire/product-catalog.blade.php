<div class="bg-white min-h-screen">
    @php
        $isHome = empty($search) && empty($selectedCategory) && empty($minPrice) && empty($maxPrice);
    @endphp

    @if($isHome)
        <!-- Home Banner -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-gray-50 border border-gray-200 h-64 sm:h-80 flex flex-col items-center justify-center text-center px-6 rounded-sm">
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-900 tracking-tight">Classic Style, Modern Comfort</h1>
                <p class="mt-4 text-xs sm:text-sm text-gray-500 max-w-lg uppercase tracking-widest font-bold">Temukan koleksi pilihan untuk gaya hidup profesional Anda.</p>
                <button wire:click="$set('selectedCategory', '')" class="mt-8 px-8 py-3 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-sm hover:bg-gray-800 transition-colors">Lihat Katalog</button>
            </div>
        </div>

        <!-- Home: Latest Products -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-900">Produk Terbaru</h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @include('livewire.partials.product-card-minimalist', ['product' => $product])
                @endforeach
            </div>
        </main>
    @else
        <!-- Catalog Page -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar -->
                <aside class="w-full md:w-64 shrink-0">
                    <div class="sticky top-24 space-y-8">
                        <!-- Category Filter -->
                        <div>
                            <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-900 mb-4 pb-2 border-b border-gray-100">Kategori</h3>
                            <div class="space-y-2">
                                <button wire:click="$set('selectedCategory', '')" class="block w-full text-left text-xs {{ empty($selectedCategory) ? 'font-bold text-gray-900' : 'text-gray-500 hover:text-gray-900' }}">Semua Produk</button>
                                @foreach($categories as $category)
                                    <button wire:click="$set('selectedCategory', {{ $category->id }})" class="block w-full text-left text-xs {{ $selectedCategory == $category->id ? 'font-bold text-gray-900' : 'text-gray-500 hover:text-gray-900' }}">{{ $category->name }}</button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Filter -->
                        <div>
                            <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-900 mb-4 pb-2 border-b border-gray-100">Harga</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <input type="number" wire:model.live.debounce.500ms="minPrice" placeholder="Min" class="w-full border-gray-200 rounded-sm text-xs focus:ring-0 focus:border-gray-900 py-2">
                                    <span class="text-gray-300">-</span>
                                    <input type="number" wire:model.live.debounce.500ms="maxPrice" placeholder="Max" class="w-full border-gray-200 rounded-sm text-xs focus:ring-0 focus:border-gray-900 py-2">
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Grid -->
                <main class="flex-1">
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            Showing {{ $products->total() }} Products
                        </div>
                        <select wire:model.live="sortBy" class="border-none text-[10px] font-bold uppercase tracking-widest text-gray-900 focus:ring-0 py-0 pr-8 cursor-pointer">
                            <option value="newest">Terbaru</option>
                            <option value="price_low">Harga: Rendah</option>
                            <option value="price_high">Harga: Tinggi</option>
                            <option value="sales">Terlaris</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse($products as $product)
                            @include('livewire.partials.product-card-minimalist', ['product' => $product])
                        @empty
                            <div class="col-span-full py-20 text-center border-2 border-dashed border-gray-100 rounded-sm">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada produk</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                </main>
            </div>
        </div>
    @endif
</div>
