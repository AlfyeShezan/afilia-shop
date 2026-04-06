<div class="max-w-3xl mx-auto px-6 py-10 space-y-12" x-data="{ showForm: false }">
    <!-- Section Header & Rating Summary -->
    <div class="space-y-6">
        <h3 class="text-xl font-semibold text-gray-800 tracking-tight">Ulasan Pelanggan</h3>
        
        <div class="flex flex-col md:flex-row md:items-center gap-10 py-6 border-y border-gray-100">
            <!-- Big Rating -->
            <div class="flex flex-col items-start gap-2">
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold text-gray-900">{{ number_format($product->average_rating, 1) }}</span>
                    <span class="text-sm text-gray-400 font-medium">/ 5.0</span>
                </div>
                <div class="flex gap-0.5 text-amber-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= round($product->average_rating) ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">{{ $product->review_count }} Total Ulasan</p>
            </div>

            <!-- Distribution Bars -->
            <div class="flex-1 space-y-2">
                @foreach([5, 4, 3, 2, 1] as $star)
                @php
                    $count = $reviews->where('rating', $star)->count();
                    $percent = $reviews->count() > 0 ? ($count / $reviews->count()) * 100 : 0;
                @endphp
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-bold text-gray-400 w-3">{{ $star }}</span>
                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gray-800 rounded-full transition-all duration-700" style="width: {{ $percent }}%"></div>
                    </div>
                    <span class="text-[10px] font-bold text-gray-500 w-8 text-right">{{ round($percent) }}%</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Review Trigger Button -->
        @if($canReview)
            <div class="flex justify-center pt-2">
                <button 
                    @click="showForm = !showForm"
                    class="w-full max-w-xs bg-gray-900 text-white rounded-md px-6 py-2.5 text-sm font-medium hover:bg-gray-800 transition-all flex items-center justify-center gap-2"
                >
                    <span x-text="showForm ? 'Batalkan Ulasan' : 'Tulis Ulasan'"></span>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="showForm ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
            </div>
        @elseif($hasReviewed)
            <div class="flex items-center justify-center gap-3 py-4 bg-gray-50 rounded-md border border-gray-100">
                <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-xs font-semibold text-gray-600">Terima kasih! Ulasan Anda sudah terkirim.</span>
            </div>
        @endif
    </div>

    <!-- Review Form (Collapsible) -->
    @if($canReview)
    <div 
        x-show="showForm" 
        x-collapse 
        x-cloak
        class="bg-white border border-gray-200 rounded-md p-6 space-y-6 shadow-sm"
    >
        <form wire:submit.prevent="saveReview" class="space-y-6">
            <!-- Star Rating Interactive -->
            <div class="flex flex-col items-center gap-3">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Rating Anda</label>
                <div class="flex gap-1.5">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" wire:click="$set('rating', {{ $i }})" class="group outline-none">
                        <svg class="w-8 h-8 transition-all {{ $rating >= $i ? 'text-amber-400 fill-current' : 'text-gray-200 fill-current group-hover:text-amber-200' }}" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                    @endfor
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Bagikan Pengalaman Anda</label>
                <textarea 
                    wire:model="comment" 
                    rows="4" 
                    class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:ring-1 focus:ring-gray-400 focus:border-gray-400 focus:outline-none transition bg-white text-gray-800 placeholder:text-gray-300"
                    placeholder="Apa yang Anda sukai dari produk ini?"
                ></textarea>
                @error('comment') <p class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</p> @enderror
            </div>

            <!-- Upload Photo -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Foto (Opsional)</label>
                    <label class="text-[10px] font-bold text-gray-900 cursor-pointer hover:underline flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        UNGGAH FOTO
                        <input type="file" wire:model="images" multiple class="hidden">
                    </label>
                </div>
                
                @if($images)
                    <div class="flex flex-wrap gap-2">
                        @foreach($images as $index => $image)
                            <div class="relative w-16 h-16 rounded-md overflow-hidden border border-gray-200 group">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                <button type="button" 
                                    wire:click="removeImage({{ $index }})" 
                                    class="absolute top-0.5 right-0.5 bg-black/50 text-white rounded-full p-0.5 hover:bg-black transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
                @error('images.*') <p class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</p> @enderror
                <p class="text-[9px] text-gray-400 uppercase tracking-wide">Maksimal 2MB per foto. Format: JPG, PNG.</p>
            </div>

            <button type="submit" class="w-full bg-gray-900 text-white rounded-md py-3 text-sm font-semibold hover:bg-gray-800 transition-all shadow-sm">
                Kirim Ulasan
            </button>
        </form>
    </div>
    @endif

    <!-- Review List -->
    <div class="space-y-8 pt-4">
        @forelse($reviews as $review)
        <div class="group" x-data="{ expanded: false }">
            <div class="bg-white p-5 rounded-md border border-gray-100 transition-colors group-hover:bg-gray-50/50">
                <!-- User Meta -->
                <div class="flex items-center justify-between gap-4 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center shrink-0 border border-gray-200 overflow-hidden">
                            @if($review->user->avatar)
                                <img src="{{ Storage::url($review->user->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-[10px] font-bold text-gray-400 uppercase">{{ substr($review->user->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-800">{{ $review->user->name }}</span>
                                <span class="flex items-center gap-1 text-[8px] font-bold uppercase tracking-widest text-green-600 bg-green-50 px-1.5 py-0.5 rounded-full border border-green-100">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Terverifikasi
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <div class="flex gap-0.5 text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-2.5 h-2.5 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>
                                <span class="text-[9px] font-medium text-gray-400 uppercase tracking-tight">{{ $review->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review Body -->
                <div class="pl-11 pr-2">
                    <div class="relative">
                        <p class="text-sm text-gray-700 leading-relaxed font-medium" :class="expanded ? '' : 'line-clamp-2'">
                            {{ $review->comment }}
                        </p>
                        @if(strlen($review->comment) > 120)
                            <button @click="expanded = !expanded" class="text-[10px] font-bold text-gray-400 uppercase mt-1 hover:text-gray-900 transition-colors">
                                <span x-text="expanded ? 'Lihat Sedikit' : 'Lihat Selengkapnya'"></span>
                            </button>
                        @endif
                    </div>

                    @if($review->images)
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($review->images as $path)
                        <div class="w-16 h-16 rounded-md overflow-hidden border border-gray-100 group/img cursor-zoom-in transition-transform hover:scale-105">
                            <img src="{{ Storage::url($path) }}" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 px-10 border border-dashed border-gray-200 rounded-md bg-gray-50/50">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <h5 class="text-xs font-bold text-gray-900 uppercase tracking-widest mb-1">Belum Ada Ulasan</h5>
            <p class="text-gray-400 font-bold uppercase tracking-widest text-[9px]">Jadilah yang pertama berbagi pengalaman Anda.</p>
        </div>
        @endforelse
    </div>
</div>
