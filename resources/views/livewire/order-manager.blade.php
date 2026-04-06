<div class="max-w-7xl mx-auto px-6 py-8 space-y-8 min-h-screen bg-gray-50">
    {{-- 1️⃣ HEADER --}}
    <div class="space-y-1">
        <p class="text-xs text-gray-400 uppercase tracking-widest">Admin / Manajemen</p>
        <h1 class="text-xl font-semibold text-gray-800">Manajemen Pesanan</h1>
        <p class="text-sm text-gray-600">Lacak dan proses logistik transaksi platform.</p>
    </div>

    {{-- NOTIFICATION --}}
    @if (session()->has('message'))
        <div class="bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest p-4 rounded-md flex justify-between items-center animate-fade-in">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white">&times;</button>
        </div>
    @endif

    {{-- 2️⃣ TOOLBAR --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari No. Pesanan, Nama atau Email..." 
                    class="w-full pl-10 border border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none transition-colors">
            </div>
            <select wire:model.live="statusFilter" 
                class="w-full md:w-auto border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-gray-400 focus:outline-none bg-white font-medium text-gray-700">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    {{-- 3️⃣ TABEL PESANAN (VERSI CLEAN) --}}
    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">ID Pesanan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Pelanggan</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-widest">Total Bayar</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-widest">Pembayaran</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr wire:key="order-{{ $order->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-800">#{{ $order->order_number }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $order->shipping_name ?? ($order->user->name ?? 'N/A') }}</div>
                            <div class="text-xs text-gray-500 lowercase">{{ $order->shipping_email ?? ($order->user->email ?? 'N/A') }}</div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-800">
                            IDR {{ number_format($order->total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-600 uppercase tracking-tighter">
                                {{ strtoupper($order->payment_method) }} • <span class="{{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-gray-400' }}">{{ strtoupper($order->payment_status) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border
                                @if($order->status === 'completed') bg-gray-100 text-gray-700 border-gray-200
                                @elseif(in_array($order->status, ['paid', 'processing'])) border-gray-300 text-gray-600
                                @elseif($order->status === 'cancelled') bg-red-50 text-red-700 border-red-100
                                @else bg-white text-gray-500 border-gray-200 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <button wire:click="viewOrder({{ $order->id }})" class="text-xs text-gray-600 hover:text-black font-medium transition-colors">Detail</button>
                                <button wire:click="confirmDelete({{ $order->id }})" class="text-xs text-red-600/70 hover:text-red-600 font-medium transition-colors">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm italic">Tidak ada pesanan ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

    {{-- Detail Pesanan Panel --}}
    @if($selectedOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-[2px] transition-opacity" wire:click="closeOrderView"></div>
        <div class="bg-gray-50 rounded-md border border-gray-200 relative w-full max-w-5xl overflow-hidden animate-slide-up flex flex-col max-h-[90vh]">
            <div class="p-6 bg-white border-b border-gray-100 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Detail Pesanan: #{{ $selectedOrder->order_number }}</h3>
                    <p class="text-xs text-gray-500">Dibuat pada {{ $selectedOrder->created_at->format('F d, Y - H:i') }}</p>
                </div>
                <button wire:click="closeOrderView" class="text-gray-400 hover:text-gray-800 transition-colors bg-white border border-gray-200 rounded-md px-3 py-1.5 text-xs font-medium">
                    Kembali
                </button>
            </div>

            <div class="p-6 overflow-y-auto space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- 1️⃣ INFORMASI PELANGGAN --}}
                    <div class="bg-white border border-gray-200 rounded-md p-6 space-y-4">
                        <p class="text-xs uppercase text-gray-500 tracking-widest font-semibold border-b border-gray-50 pb-2">Informasi Pelanggan</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs uppercase text-gray-500 mb-1">Nama Lengkap</p>
                                <p class="text-sm text-gray-800 font-medium">{{ $selectedOrder->shipping_name ?? ($selectedOrder->user->name ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase text-gray-500 mb-1">Email Address</p>
                                <p class="text-sm text-gray-800 font-medium lowercase">{{ $selectedOrder->shipping_email ?? ($selectedOrder->user->email ?? 'N/A') }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs uppercase text-gray-500 mb-1">Nomor Telepon</p>
                                <p class="text-sm text-gray-800 font-medium">{{ $selectedOrder->shipping_phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- 2️⃣ ALAMAT LOGISTIK --}}
                    <div class="bg-white border border-gray-200 rounded-md p-6 space-y-4">
                        <p class="text-xs uppercase text-gray-500 tracking-widest font-semibold border-b border-gray-50 pb-2">Alamat Logistik</p>
                        <div>
                            <p class="text-xs uppercase text-gray-500 mb-1">Alamat Pengiriman</p>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                {{ $selectedOrder->shipping_address }}<br>
                                {{ $selectedOrder->shipping_city }}, {{ $selectedOrder->shipping_state }} {{ $selectedOrder->shipping_zip }}<br>
                                {{ $selectedOrder->shipping_country }}
                            </p>
                        </div>
                    </div>

                    {{-- 3️⃣ STATUS & KONTROL --}}
                    <div class="bg-white border border-gray-200 rounded-md p-6 space-y-4 md:col-span-2">
                        <div class="flex flex-col md:flex-row justify-between gap-6">
                            <div class="space-y-1">
                                <p class="text-xs uppercase text-gray-500 mb-1">Metode Pembayaran</p>
                                <p class="text-sm text-gray-800 font-medium uppercase tracking-tight">{{ $selectedOrder->payment_method }} • <span class="text-blue-600">{{ $selectedOrder->payment_status }}</span></p>
                            </div>
                            <div class="flex flex-col md:flex-row items-end md:items-center gap-4">
                                <div class="w-full md:w-48">
                                    <label class="block text-xs text-gray-500 mb-1.5 uppercase font-semibold">Update Progres</label>
                                    <select wire:model="newStatus" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white">
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <button wire:click="updateStatus" class="bg-gray-900 text-white rounded-md px-6 py-2 h-10 text-sm font-medium hover:bg-gray-800 transition-colors">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4️⃣ DAFTAR ITEM PESANAN --}}
                <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
                    <div class="px-6 py-3 bg-gray-50 border-b border-gray-100">
                        <p class="text-xs uppercase text-gray-500 tracking-widest font-semibold">Daftar Item Pesanan</p>
                    </div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase">Produk</th>
                                <th class="px-6 py-3 text-right text-xs text-gray-500 uppercase">Harga</th>
                                <th class="px-6 py-3 text-center text-xs text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-right text-xs text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($selectedOrder->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5 uppercase tracking-tighter">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-600">IDR {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-800">IDR {{ number_format($item->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- 5️⃣ RINGKASAN TOTAL --}}
                <div class="flex justify-end">
                    <div class="w-full md:w-80 space-y-3 p-4 bg-white border border-gray-200 rounded-md">
                        <div class="flex justify-between text-xs text-gray-500 uppercase">
                            <span>Net Subtotal</span>
                            <span class="text-gray-800">IDR {{ number_format($selectedOrder->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($selectedOrder->discount > 0)
                        <div class="flex justify-between text-xs text-red-600 uppercase font-semibold">
                            <span>Voucher Discount</span>
                            <span>-IDR {{ number_format($selectedOrder->discount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-xs text-gray-500 uppercase">
                            <span>Biaya Pengiriman</span>
                            <span class="text-gray-800">IDR {{ number_format($selectedOrder->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-900 uppercase">Grand Total</span>
                            <span class="text-lg font-bold text-gray-900 tracking-tight">IDR {{ number_format($selectedOrder->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border-t border-gray-100 flex items-center justify-between shrink-0">
                <button wire:click="confirmDelete({{ $selectedOrder->id }})" class="text-xs text-gray-400 hover:text-red-600 transition-colors font-medium">
                    Hapus Pesanan
                </button>
                <button wire:click="closeOrderView" class="bg-gray-900 text-white rounded-md px-6 py-2.5 text-sm font-medium hover:bg-gray-800 transition-colors">
                    Tutup Panel
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- DELETE CONFIRMATION --}}
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-[2px] transition-opacity" wire:click="$set('confirmingDeletion', null)"></div>
        <div class="bg-white rounded-md border border-gray-200 relative w-full max-w-sm overflow-hidden animate-slide-up">
            <div class="p-8 text-center space-y-4">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-50 text-red-600 border border-red-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-semibold text-gray-800">Hapus Pesanan?</h3>
                    <p class="text-sm text-gray-500">Penghapusan ini permanen untuk database logistik.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-4">
                    <button wire:click="deleteOrder" class="bg-red-600 text-white rounded-md py-2.5 text-sm font-medium hover:bg-red-700 transition-colors">Hapus</button>
                    <button wire:click="$set('confirmingDeletion', null)" class="bg-white border border-gray-300 text-gray-600 rounded-md py-2.5 text-sm font-medium hover:bg-gray-50 transition-colors">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes slide-up { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-in { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }
        .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    </style>
</div>
