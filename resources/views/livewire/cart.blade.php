<div class="py-12 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl font-bold text-gray-900 mb-8 uppercase tracking-widest">Keranjang Belanja</h1>

        @if($cartItems->isEmpty())
            <div class="py-24 text-center max-w-lg mx-auto border border-gray-100 rounded-sm">
                <div class="w-12 h-12 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-widest mb-2">Keranjang Kosong</h2>
                <p class="text-[11px] text-gray-400 mb-8 uppercase tracking-widest">Mulai belanja produk pilihan kami.</p>
                <a href="{{ route('home') }}" class="inline-block bg-gray-900 text-white px-8 py-3 rounded-sm text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all">
                    Kembali Belanja
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-12 items-start">
                <!-- Left: Cart Items -->
                <div class="flex-1 w-full space-y-6">
                    <!-- Select All -->
                    <div class="bg-white p-4 border border-gray-100 rounded-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model.live="selectAll" id="selectAll" 
                                class="w-4 h-4 rounded-sm border-gray-300 text-gray-900 focus:ring-0 cursor-pointer">
                            <label for="selectAll" class="text-[10px] font-bold text-gray-600 uppercase tracking-widest cursor-pointer">Pilih Semua</label>
                        </div>
                        @if(count($selectedItems) > 0)
                        <button wire:click="clearCart" wire:confirm="Hapus {{ count($selectedItems) }} produk?" 
                            class="text-[9px] font-bold text-red-500 hover:underline uppercase tracking-widest">
                            Hapus
                        </button>
                        @endif
                    </div>

                    @php
                        $groupedItems = $cartItems->groupBy(function($item) {
                            return $item->product->vendor->name ?? 'Toko Afilia';
                        });
                    @endphp

                    @foreach($groupedItems as $vendorName => $items)
                        <div class="border border-gray-100 rounded-sm overflow-hidden">
                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-100">
                                <span class="text-[9px] font-bold text-gray-900 uppercase tracking-widest">{{ $vendorName }}</span>
                            </div>

                            <div class="divide-y divide-gray-50">
                                @foreach($items as $item)
                                    <div class="p-4 flex gap-4 items-start bg-white">
                                        <div class="pt-1">
                                            <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}" 
                                                class="w-4 h-4 rounded-sm border-gray-300 text-gray-900 focus:ring-0 cursor-pointer">
                                        </div>

                                        <a href="{{ route('product.detail', $item->product->slug) }}" wire:navigate class="w-20 aspect-video bg-gray-50 border border-gray-100 rounded-sm shrink-0 flex items-center justify-center p-2">
                                            @php $primaryImage = $item->product->images->where('is_primary', true)->first(); @endphp
                                            @if($primaryImage)
                                                <img src="{{ Storage::url($primaryImage->image_path) }}" class="w-full h-full object-contain">
                                            @endif
                                        </a>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                                <div class="flex-1">
                                                    <a href="{{ route('product.detail', $item->product->slug) }}" wire:navigate>
                                                        <h3 class="font-bold text-gray-900 text-xs mb-1 hover:underline line-clamp-2">{{ $item->product->name }}</h3>
                                                    </a>
                                                    
                                                    @if(!empty($item->metadata))
                                                        <div class="flex gap-2 mt-1">
                                                            @foreach($item->metadata as $label => $val)
                                                                <span class="text-[8px] font-bold text-gray-400 uppercase">{{ $label }}: {{ $val }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    <div class="mt-2 text-xs font-bold text-gray-900">
                                                        Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-4 self-end md:self-auto">
                                                    <div class="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white transition focus-within:ring-2 focus-within:ring-gray-300 focus-within:border-gray-400">
                                                        <button wire:click="updateQuantity('{{ $item->id }}', {{ $item->quantity - 1 }})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                                        <span class="w-8 text-center text-xs font-bold text-gray-900 border-x border-gray-300 h-8 flex items-center justify-center">{{ $item->quantity }}</span>
                                                        <button wire:click="updateQuantity('{{ $item->id }}', {{ $item->quantity + 1 }})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">+</button>
                                                    </div>
                                                    <button wire:click="removeItem('{{ $item->id }}')" class="text-gray-400 hover:text-red-500">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right: Summary -->
                <div class="w-full lg:w-[320px] shrink-0">
                    <div class="border border-gray-100 rounded-sm p-6 sticky top-24 space-y-6 bg-white">
                        <h2 class="text-[10px] font-bold text-gray-900 uppercase tracking-widest border-b border-gray-50 pb-4">Ringkasan Pesanan</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-[11px] text-gray-500 font-bold uppercase tracking-widest">
                                <span>Subtotal</span>
                                <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-[11px] text-gray-500 font-bold uppercase tracking-widest">
                                <span>Estimasi Kirim</span>
                                <span class="text-gray-900">Rp 0</span>
                            </div>
                            <div class="pt-4 flex justify-between items-baseline border-t border-gray-100">
                                <span class="text-xs font-bold text-gray-900 uppercase tracking-widest">Total</span>
                                <span class="text-lg font-bold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button wire:click="checkout" 
                            @if(count($selectedItems) === 0) disabled @endif
                            class="w-full bg-gray-900 text-white py-4 rounded-sm text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all disabled:bg-gray-100 disabled:text-gray-400">
                            Lanjut ke Pembayaran
                        </button>
                        
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest text-center">Keamanan Transaksi Terjamin</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
