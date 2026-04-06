<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Voucher Tersedia</h1>
        <p class="text-sm text-gray-500 mt-1">Cek voucher aktif dan gunakan kode saat checkout untuk mendapatkan diskon.</p>
    </div>

    {{-- Input Cek Voucher --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-sm font-semibold text-slate-700 mb-4">Punya kode voucher?</h2>
        <div class="flex gap-3">
            <input wire:model="voucherCode" type="text" placeholder="Masukkan kode voucher..."
                class="flex-1 h-11 bg-gray-50 border-gray-200 rounded-xl font-medium text-slate-900 focus:ring-4 focus:ring-indigo-50/50 focus:border-indigo-600 transition-all px-4 text-sm uppercase">
            <button wire:click="claimVoucher" class="px-6 h-11 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-slate-900 transition-all shadow-sm">
                Cek
            </button>
        </div>
        @if($message)
            <div class="mt-3 flex items-center gap-2 text-sm font-medium {{ $messageType === 'success' ? 'text-green-600' : 'text-red-500' }}">
                @if($messageType === 'success')
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                @else
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                @endif
                {{ $message }}
            </div>
        @endif
    </div>

    {{-- Daftar Voucher --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse($vouchers as $voucher)
        <div class="bg-white rounded-2xl border border-indigo-100 overflow-hidden shadow-sm hover:shadow-md transition-all">
            <div class="flex">
                {{-- Left accent --}}
                <div class="w-2 bg-indigo-600 shrink-0"></div>
                <div class="flex-1 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="inline-block px-2 py-0.5 text-[10px] font-semibold text-indigo-600 bg-indigo-50 rounded-md mb-2">Aktif</span>
                            <h3 class="text-lg font-bold text-slate-900">
                                @if($voucher->type === 'percentage')
                                    Diskon {{ $voucher->value }}%
                                    @if($voucher->max_discount)
                                        <span class="text-sm font-normal text-gray-400"> (maks. Rp {{ number_format($voucher->max_discount, 0, ',', '.') }})</span>
                                    @endif
                                @else
                                    Diskon Rp {{ number_format($voucher->value, 0, ',', '.') }}
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $voucher->name }}</p>
                            @if($voucher->min_spend > 0)
                            <p class="text-xs text-gray-400 mt-2">Min. belanja Rp {{ number_format($voucher->min_spend, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        <div class="shrink-0 bg-gray-50 rounded-xl px-3 py-2 text-center border border-gray-100">
                            <p class="text-[9px] font-semibold uppercase tracking-widest text-gray-400 mb-1">Kode</p>
                            <p class="text-sm font-bold text-slate-900 font-mono tracking-wider">{{ $voucher->code }}</p>
                        </div>
                    </div>
                    @if($voucher->expires_at)
                    <p class="text-xs text-gray-400 mt-4 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Berlaku s/d {{ $voucher->expires_at->format('d M Y') }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-2 text-center py-16 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
            <svg class="w-10 h-10 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4v-3a2 2 0 00-2-2H5z"/>
            </svg>
            <h3 class="text-base font-bold text-slate-700 mb-1">Belum ada voucher aktif</h3>
            <p class="text-sm text-gray-400">Pantau terus halaman ini untuk promo terbaru.</p>
        </div>
        @endforelse
    </div>
</div>
