<div class="py-8 sm:py-12 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $steps = [
                1 => 'Pengiriman',
                2 => 'Tinjauan',
                3 => 'Pembayaran'
            ];
        @endphp

        <!-- Minimalist Steps -->
        <div class="mb-10 sm:mb-12 max-w-xl mx-auto">
            <div class="flex items-center justify-between gap-2 sm:gap-4">
                @foreach($steps as $step => $label)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 flex items-center justify-center text-[10px] font-bold border {{ $currentStep >= $step ? 'bg-gray-900 border-gray-900 text-white' : 'bg-white border-gray-200 text-gray-400' }} rounded-sm shrink-0">
                            {{ $step }}
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-widest {{ $currentStep >= $step ? 'text-gray-900' : 'text-gray-400' }} hidden sm:block">{{ $label }}</span>
                    </div>
                    @if($step < count($steps))
                        <div class="flex-1 h-px bg-gray-100"></div>
                    @endif
                @endforeach
            </div>
        </div>

        @if($currentStep == 2)
            {{-- Step 2: Tinjauan (Single Column) --}}
            <div class="max-w-2xl mx-auto space-y-6">
                {{-- Header --}}
                <div class="pb-2">
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Tinjau Pesanan</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Toko Afilia</p>
                </div>

                {{-- Product List --}}
                <div class="space-y-4">
                    @php
                        $groupedItems = $cartItems->groupBy(function($item) {
                            return $item->product->vendor->name ?? 'Toko Afilia';
                        });
                    @endphp

                    @foreach($groupedItems as $vendorName => $items)
                        <div class="border border-gray-200 rounded-md overflow-hidden bg-white">
                            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">{{ $vendorName }}</h3>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach($items as $item)
                                    <div class="p-5 flex gap-5">
                                        <div class="w-16 h-16 bg-white border border-gray-200 rounded-md overflow-hidden shrink-0">
                                            @php $primaryImage = $item->product->images->where('is_primary', true)->first() ?? $item->product->images->first(); @endphp
                                            @if($primaryImage)
                                                <img src="{{ Storage::url($primaryImage->image_path) }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                                            <div>
                                                <h4 class="font-medium text-gray-800 text-sm mb-1">{{ $item->product->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Shipping Address --}}
                <div class="border border-gray-200 rounded-md bg-white p-5">
                    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Alamat Pengiriman</h3>
                    <div class="space-y-2">
                        <p class="text-sm font-bold text-gray-800">{{ $name }}</p>
                        <p class="text-xs text-gray-600 leading-relaxed">{{ $address }}, {{ $city }}, {{ $state }} {{ $zip }}</p>
                        <p class="text-xs text-gray-600">{{ $phone }}</p>
                    </div>
                </div>

                {{-- Order Summary, Vouchers & Points --}}
                <div class="border border-gray-200 rounded-md bg-white p-6 space-y-6">
                    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-3">Ringkasan Pesanan</h3>
                    
                    {{-- Voucher --}}
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Punya Kode Voucher?</label>
                        @if($appliedVoucherCode)
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-md">
                                <div>
                                    <span class="text-[9px] font-bold text-gray-900 uppercase tracking-widest block">Voucher Terpasang</span>
                                    <span class="text-xs font-bold text-blue-600">{{ $appliedVoucherCode }}</span>
                                </div>
                                <button wire:click="removeVoucher" class="text-[9px] font-bold text-red-600 uppercase tracking-widest hover:underline">Hapus</button>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <input wire:model="voucherCode" wire:keydown.enter="applyVoucher" type="text" placeholder="Masukkan kode..." class="flex-1 border border-gray-300 rounded-md px-4 py-2 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-gray-400 transition uppercase">
                                <button wire:click="applyVoucher" class="px-6 bg-gray-900 text-white text-[10px] font-bold uppercase rounded-md hover:bg-black transition-all">Gunakan</button>
                            </div>
                        @endif
                    </div>

                    {{-- Points --}}
                    @if(Auth::check() && $userPoints > 0)
                        <label class="flex items-center justify-between p-4 border border-gray-100 rounded-md cursor-pointer bg-gray-50/50 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-bold text-gray-900 uppercase tracking-widest">Gunakan Poin Afilia</span>
                                <span class="text-[10px] font-bold text-gray-500 mt-0.5">Saldo: {{ number_format($userPoints, 0, ',', '.') }}</span>
                            </div>
                            <input type="checkbox" wire:model.live="usePoints" class="w-4 h-4 rounded-sm border-gray-300 text-gray-900 focus:ring-0">
                        </label>
                    @endif

                    {{-- Breakdown --}}
                    <div class="space-y-3 pt-2">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Subtotal</span>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Pajak (10%)</span>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Ongkos Kirim</span>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        @if($pointsDiscount > 0)
                            <div class="flex justify-between text-xs text-blue-600 font-bold">
                                <span class="uppercase tracking-widest text-[9px]">Diskon Poin</span>
                                <span>-Rp {{ number_format($pointsDiscount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($voucherDiscount > 0)
                            <div class="flex justify-between text-xs text-blue-600 font-bold">
                                <span class="uppercase tracking-widest text-[9px]">Diskon Voucher</span>
                                <span>-Rp {{ number_format($voucherDiscount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-baseline pt-4 border-t border-gray-100">
                            <span class="text-sm font-bold text-gray-900 uppercase tracking-tight">Total</span>
                            <span class="text-2xl font-bold text-gray-900 tracking-tighter">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-3 pt-4">
                    <button wire:click="nextStep" class="w-full bg-gray-900 text-white py-4 rounded-md text-sm font-bold uppercase tracking-[0.2em] hover:bg-black transition-all">
                        Lanjut Pembayaran
                    </button>
                    <button wire:click="previousStep" class="w-full border border-gray-300 bg-white text-gray-700 py-3 rounded-md text-sm font-bold uppercase tracking-[0.2em] hover:bg-gray-50 transition-all">
                        Kembali Ke Pengiriman
                    </button>
                </div>
            </div>

        @elseif($currentStep == 3)
            {{-- Step 3: Pembayaran (Single Column) --}}
            <div class="max-w-2xl mx-auto space-y-6">
                {{-- Header --}}
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Metode Pembayaran</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Pilih cara pembayaran Anda</p>
                </div>

                {{-- Payment Methods --}}
                <div class="space-y-3">
                    {{-- Midtrans --}}
                    <label class="block group cursor-pointer">
                        <input type="radio" wire:model.live="paymentMethod" value="midtrans" class="sr-only">
                        <div class="flex items-start gap-4 p-4 border rounded-md transition-all {{ $paymentMethod == 'midtrans' ? 'border-gray-500 bg-gray-50' : 'border-gray-200 hover:border-gray-300 bg-white' }}">
                            <div class="mt-1">
                                <div class="w-4 h-4 border-2 rounded-full flex items-center justify-center {{ $paymentMethod == 'midtrans' ? 'border-gray-800' : 'border-gray-300' }}">
                                    @if($paymentMethod == 'midtrans')
                                        <div class="w-2 h-2 bg-gray-800 rounded-full"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-medium text-gray-800">Midtrans (Otomatis)</h4>
                                    <div class="flex gap-2 opacity-60">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-0.5 leading-relaxed">Virtual Account, E-Wallet (Gopay, OVO, QRIS), Kartu Kredit.</p>
                            </div>
                        </div>
                    </label>

                    {{-- COD --}}
                    <label class="block group cursor-pointer">
                        <input type="radio" wire:model.live="paymentMethod" value="cod" class="sr-only">
                        <div class="flex items-start gap-4 p-4 border rounded-md transition-all {{ $paymentMethod == 'cod' ? 'border-gray-500 bg-gray-50' : 'border-gray-200 hover:border-gray-300 bg-white' }}">
                            <div class="mt-1">
                                <div class="w-4 h-4 border-2 rounded-full flex items-center justify-center {{ $paymentMethod == 'cod' ? 'border-gray-800' : 'border-gray-300' }}">
                                    @if($paymentMethod == 'cod')
                                        <div class="w-2 h-2 bg-gray-800 rounded-full"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-medium text-gray-800">Bayar di Tempat (COD)</h4>
                                    <div class="opacity-60">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-0.5 leading-relaxed">Bayar tunai saat pesanan Anda sampai di tujuan.</p>
                            </div>
                        </div>
                    </label>
                </div>

                {{-- Summary Card --}}
                <div class="border border-gray-200 rounded-md bg-white p-5 md:p-6 space-y-6">
                    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-3">Ringkasan Pesanan</h3>

                    {{-- Voucher --}}
                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Punya Kode Voucher?</label>
                        @if($appliedVoucherCode)
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-md">
                                <div>
                                    <span class="text-[9px] font-bold text-gray-900 uppercase tracking-widest block">Voucher Terpasang</span>
                                    <span class="text-xs font-bold text-blue-600">{{ $appliedVoucherCode }}</span>
                                </div>
                                <button wire:click="removeVoucher" class="text-[9px] font-bold text-red-600 uppercase tracking-widest hover:underline">Hapus</button>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <input wire:model="voucherCode" wire:keydown.enter="applyVoucher" type="text" placeholder="Masukkan kode..." class="flex-1 border border-gray-300 rounded-md px-4 py-2 text-xs font-bold focus:outline-none focus:ring-1 focus:ring-gray-400 transition uppercase">
                                <button wire:click="applyVoucher" class="px-6 bg-gray-900 text-white text-[10px] font-bold uppercase rounded-md hover:bg-black transition-all">Gunakan</button>
                            </div>
                        @endif
                    </div>

                    {{-- Points --}}
                    @if(Auth::check() && $userPoints > 0)
                        <label class="flex items-center justify-between p-4 border border-gray-100 rounded-md cursor-pointer bg-gray-50/50 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-bold text-gray-900 uppercase tracking-widest">Gunakan Poin Afilia</span>
                                <span class="text-[10px] font-bold text-gray-500 mt-0.5">Saldo: {{ number_format($userPoints, 0, ',', '.') }}</span>
                            </div>
                            <input type="checkbox" wire:model.live="usePoints" class="w-4 h-4 rounded-sm border-gray-300 text-gray-900 focus:ring-0">
                        </label>
                    @endif

                    {{-- Breakdown --}}
                    <div class="space-y-3 pt-2">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Subtotal</span>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Pajak (10%)</span>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Ongkos Kirim</span>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        @if($pointsDiscount > 0)
                            <div class="flex justify-between text-xs text-blue-600 font-bold">
                                <span class="uppercase tracking-widest text-[9px]">Diskon Poin</span>
                                <span>-Rp {{ number_format($pointsDiscount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($voucherDiscount > 0)
                            <div class="flex justify-between text-xs text-blue-600 font-bold">
                                <span class="uppercase tracking-widest text-[9px]">Diskon Voucher</span>
                                <span>-Rp {{ number_format($voucherDiscount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-baseline pt-4 border-t border-gray-100">
                            <span class="text-sm font-bold text-gray-900 uppercase tracking-tight">Total</span>
                            <span class="text-2xl font-bold text-gray-900 tracking-tighter">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="space-y-3 pt-4">
                    <button wire:click="placeOrder" wire:loading.attr="disabled" class="w-full bg-gray-900 text-white py-4 rounded-md text-sm font-bold uppercase tracking-[0.2em] hover:bg-black transition-all">
                        <span wire:loading.remove>Buat Pesanan</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                    <button wire:click="previousStep" class="w-full border border-gray-300 bg-white text-gray-700 py-3 rounded-md text-sm font-bold uppercase tracking-[0.2em] hover:bg-gray-50 transition-all">
                        Kembali Ke Tinjauan
                    </button>
                </div>
            </div>

        @else
            {{-- Step 1: Pengiriman (Two Column) --}}
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                <!-- Main Content -->
                <div class="flex-1">
                    <div class="space-y-8">
                        <div class="border border-gray-100 rounded-sm overflow-hidden relative">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-[11px] font-bold text-gray-900 uppercase tracking-widest">Alamat Pengiriman</h2>
                                    <a href="{{ route('addresses') }}" wire:navigate class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-gray-900 underline">Ubah</a>
                                </div>

                                @if(count($savedAddresses) > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        @foreach($savedAddresses as $addr)
                                            <button type="button" wire:click="selectAddress({{ $addr['id'] }})"
                                                class="text-left p-4 border transition-all rounded-sm {{ $selectedAddressId == $addr['id'] ? 'border-gray-900 bg-gray-50' : 'border-gray-100 hover:border-gray-300' }}">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="text-[8px] font-bold uppercase tracking-widest {{ $selectedAddressId == $addr['id'] ? 'text-gray-900' : 'text-gray-400' }}">{{ $addr['label'] }}</span>
                                                    @if($addr['is_default'])
                                                        <span class="px-2 py-0.5 bg-gray-200 rounded-sm text-[7px] font-bold text-gray-700 uppercase tracking-widest">Utama</span>
                                                    @endif
                                                </div>
                                                <p class="text-[11px] font-bold text-gray-900">{{ $addr['recipient_name'] }}</p>
                                                <p class="text-[10px] text-gray-500 mt-1 leading-relaxed line-clamp-2 md:line-clamp-1">{{ $addr['full_address'] }}</p>
                                            </button>
                                        @endforeach
                                    </div>
                                    <div class="relative py-4">
                                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-50"></div></div>
                                        <div class="relative flex justify-center text-[9px] uppercase font-bold text-gray-300 bg-white px-4">Atau Isi Manual</div>
                                    </div>
                                @endif

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                                    <div class="sm:col-span-2">
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Penerima <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition text-xs font-bold text-gray-900 placeholder:text-gray-300">
                                        @error('name') <span class="text-red-500 text-[9px] font-bold uppercase tracking-widest mt-1 block"> {{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-2">Email <span class="text-red-500">*</span></label>
                                        <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition text-xs font-bold text-gray-900">
                                        @error('email') <span class="text-red-500 text-[9px] font-bold uppercase tracking-widest mt-1 block"> {{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-2">WhatsApp <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="phone" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition text-xs font-bold text-gray-900">
                                        @error('phone') <span class="text-red-500 text-[9px] font-bold uppercase tracking-widest mt-1 block"> {{ $message }}</span> @enderror
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                                        <textarea wire:model="address" rows="2" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition text-xs font-bold text-gray-900"></textarea>
                                        @error('address') <span class="text-red-500 text-[9px] font-bold uppercase tracking-widest mt-1 block"> {{ $message }}</span> @enderror
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:col-span-2">
                                        <div>
                                            <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-2">Kota <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="city" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition text-xs font-bold text-gray-900">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-2">Provinsi <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="state" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition text-xs font-bold text-gray-900">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-2">Kodepos <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="zip" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition text-xs font-bold text-gray-900">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Sidebar (Step 1 Only) -->
                <div class="w-full lg:w-[320px] shrink-0">
                    <div class="border border-gray-100 rounded-sm p-6 sticky top-24 space-y-6 bg-white">
                        <h2 class="text-[10px] font-bold text-gray-900 uppercase tracking-widest border-b border-gray-50 pb-4">Ringkasan Pesanan</h2>
                        
                        {{-- Vouchers & Points --}}
                        <div class="space-y-4">
                            @if($appliedVoucherCode)
                                <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-100 rounded-sm">
                                    <span class="text-[9px] font-bold text-gray-900 uppercase tracking-widest">{{ $appliedVoucherCode }}</span>
                                    <button wire:click="removeVoucher" class="text-[8px] font-bold text-red-500 hover:underline uppercase tracking-widest">Hapus</button>
                                </div>
                            @else
                                <div class="flex gap-2">
                                    <input wire:model="voucherCode" wire:keydown.enter="applyVoucher" type="text" placeholder="Voucher..." class="flex-1 border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition text-[10px] font-bold text-gray-900 uppercase">
                                    <button wire:click="applyVoucher" class="px-4 h-10 bg-gray-900 text-white text-[9px] font-bold uppercase rounded-sm hover:bg-gray-800 transition-all">Gunakan</button>
                                </div>
                            @endif

                            @if(Auth::check() && $userPoints > 0)
                                <label class="flex items-center justify-between p-3 border border-gray-100 rounded-sm cursor-pointer group hover:bg-gray-50">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-bold text-gray-900 uppercase tracking-widest">Gunakan Poin</span>
                                        <span class="text-[8px] font-bold text-gray-400 uppercase">Saldo: {{ number_format($userPoints, 0, ',', '.') }}</span>
                                    </div>
                                    <input type="checkbox" wire:model.live="usePoints" class="w-4 h-4 rounded-sm border-gray-300 text-gray-900 focus:ring-0">
                                </label>
                            @endif
                        </div>

                        {{-- Breakdown --}}
                        <div class="space-y-3 pt-4 border-t border-gray-50 font-bold uppercase tracking-widest text-[9px]">
                            <div class="flex justify-between text-gray-400">
                                <span>Subtotal</span>
                                <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span>Pajak (10%)</span>
                                <span class="text-gray-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span>Kirim</span>
                                <span class="text-gray-900">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            @if($pointsDiscount > 0)
                                <div class="flex justify-between text-gray-900">
                                    <span>Poin</span>
                                    <span>-Rp {{ number_format($pointsDiscount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($voucherDiscount > 0)
                                <div class="flex justify-between text-gray-900">
                                    <span>Voucher</span>
                                    <span>-Rp {{ number_format($voucherDiscount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Total --}}
                        <div class="pt-4 border-t border-gray-100 flex justify-between items-baseline mb-6">
                            <span class="text-[11px] font-bold text-gray-900 uppercase tracking-widest">Total</span>
                            <span class="text-xl font-bold text-gray-900 tracking-tight">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        {{-- Actions --}}
                        <div class="space-y-3">
                            <button wire:click="nextStep" class="w-full bg-gray-900 text-white py-4 rounded-sm text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all">
                                Lanjut Tinjauan
                            </button>
                            <a href="{{ route('cart') }}" class="block text-center text-[9px] font-bold uppercase tracking-widest text-gray-400 hover:text-gray-900 py-2">Batal & Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @script
    <script>
        Livewire.on('pay-midtrans', (event) => {
            const data = event[0];
            window.snap.pay(data.snapToken, {
                onSuccess: function(result) {
                    $wire.handleMidtransSuccess(data.orderNumber).then(() => {
                        window.location.href = "{{ route('order.history') }}";
                    });
                },
                onPending: function(result) {
                    window.location.href = "{{ route('order.history') }}";
                },
                onError: function(result) {
                    Livewire.dispatch('notify', [{ message: 'Pembayaran gagal!', type: 'error' }]);
                },
                onClose: function() {
                    Livewire.dispatch('notify', [{ message: 'Anda menutup jendela pembayaran.', type: 'info' }]);
                }
            });
        });
    </script>
    @endscript
</div>
