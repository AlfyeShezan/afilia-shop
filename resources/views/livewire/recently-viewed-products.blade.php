<div>
    @if($products && $products->count() > 0)
    <div class="mt-24 border-t border-gray-100 pt-12">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest text-gray-900">Terakhir Dilihat</h2>
            </div>
            <button wire:click="$refresh" class="text-[9px] font-bold text-gray-400 hover:text-gray-900 uppercase tracking-widest">Segarkan</button>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                @include('livewire.partials.product-card-minimalist', ['product' => $product])
            @endforeach
        </div>
    </div>
    @endif
</div>
