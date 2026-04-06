<div class="bg-white min-h-screen">
    <div class="max-w-3xl mx-auto pb-24">
        <!-- Minimalist Tabs -->
        <div class="sticky top-0 z-20 bg-white border-b border-gray-100 mb-6">
            <div class="flex overflow-x-auto no-scrollbar">
                @php
                    $tabs = [
                        ['id' => 'all', 'label' => 'Semua'],
                        ['id' => 'pending', 'label' => 'Bayar'],
                        ['id' => 'processing', 'label' => 'Kemas'],
                        ['id' => 'shipped', 'label' => 'Kirim'],
                        ['id' => 'completed', 'label' => 'Selesai'],
                        ['id' => 'cancelled', 'label' => 'Batal'],
                    ];
                @endphp
                @foreach($tabs as $tab)
                    <button wire:click="setStatus('{{ $tab['id'] }}')" 
                            class="flex-1 min-w-[80px] py-4 text-center text-[10px] uppercase font-bold tracking-widest transition-all relative
                            {{ $status === $tab['id'] ? 'text-gray-900 border-b-2 border-gray-900' : 'text-gray-400 hover:text-gray-600' }}">
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Orders List -->
        <div class="px-4 space-y-6">
            @if($orders->count() > 0)
                @foreach($orders as $order)
                    <div class="border border-gray-100 rounded-sm overflow-hidden bg-white">
                        <!-- Card Header -->
                        <div class="px-4 py-2 flex items-center justify-between border-b border-gray-50 bg-gray-50">
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] font-bold text-gray-900 uppercase tracking-widest">Afilia Market</span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase">#{{ $order->order_number }}</span>
                            </div>
                            <span class="text-[9px] font-bold uppercase tracking-widest 
                                {{ in_array($order->status, ['completed', 'shipped']) ? 'text-green-600' : ($order->status === 'cancelled' ? 'text-red-500' : 'text-gray-600') }}">
                                {{ $order->status }}
                            </span>
                        </div>

                        <!-- Card Content -->
                        <div class="p-4 space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex gap-4">
                                    <div class="w-16 aspect-video bg-white border border-gray-50 rounded-sm overflow-hidden shrink-0 p-1">
                                        @if($item->product->images->count() > 0)
                                            <img src="{{ Storage::url($item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-gray-900 line-clamp-1 mb-1">{{ $item->product->name }}</h4>
                                        <div class="flex items-center justify-between text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                            <span>{{ $item->quantity }} Barang</span>
                                            <span class="text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Card Footer -->
                        <div class="px-4 py-4 border-t border-gray-50 bg-white">
                            <div class="flex flex-col md:flex-row items-end md:items-center justify-between gap-4">
                                <div class="flex items-center gap-2 order-2 md:order-1">
                                    <a href="{{ route('invoice.show', $order->order_number) }}" target="_blank"
                                       class="px-4 py-2 border border-gray-200 text-gray-600 text-[9px] font-bold uppercase tracking-widest rounded-sm hover:border-gray-900 hover:text-gray-900 transition-all">
                                        Invoice
                                    </a>
                                    <button wire:click="buyAgain({{ $order->id }})" wire:loading.attr="disabled"
                                            class="px-4 py-2 bg-gray-900 text-white text-[9px] font-bold uppercase tracking-widest rounded-sm hover:bg-gray-800 transition-all">
                                        <span wire:loading.remove wire:target="buyAgain({{ $order->id }})">Beli Lagi</span>
                                        <span wire:loading wire:target="buyAgain({{ $order->id }})">...</span>
                                    </button>
                                </div>
                                <div class="flex items-center gap-2 order-1 md:order-2">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Belanja</span>
                                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="py-24 text-center border border-gray-100 rounded-sm">
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Belum ada pesanan</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block text-[10px] font-bold text-gray-900 uppercase underline tracking-widest">Mulai Belanja</a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
