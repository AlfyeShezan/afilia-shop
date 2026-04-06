<div class="p-6 bg-white min-h-screen">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">Penarikan Dana</h1>
                <p class="text-sm text-gray-500 mt-1 italic">Proses penarikan pendapatan toko Anda</p>
            </div>
            <a href="{{ route('vendor.dashboard') }}" class="px-3 py-1 bg-gray-100 border border-gray-300 rounded text-[10px] font-bold text-gray-700 hover:bg-gray-200 uppercase tracking-widest transition-all">
                &lsaquo; Kembali ke Buku Kas
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Request Section -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Balance Overview Table -->
                <div class="border border-gray-200 rounded overflow-hidden">
                    <table class="w-full text-sm">
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-4 py-2 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Keuangan</th>
                            <th class="px-4 py-2 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Jumlah Rupiah</th>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-600 italic">Saldo Tersedia</td>
                            <td class="px-4 py-3 text-right font-black text-indigo-700 underline">Rp {{ number_format($vendor->balance, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Form Section -->
                <div class="border border-gray-200 p-6 rounded bg-gray-50">
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 border-b pb-2">Permintaan Penarikan</h2>
                    
                    <form wire:submit.prevent="requestWithdrawal" class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Jumlah yang Diminta</label>
                            <input wire:model="amount" type="number" step="0.01" class="w-full h-10 bg-white border border-gray-300 rounded px-3 text-lg font-bold text-gray-900 focus:ring-1 focus:ring-indigo-500 transition-all" placeholder="0">
                            @error('amount') <span class="text-[9px] text-red-600 font-bold mt-1 block uppercase italic">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2 pt-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 border-t pt-2">Detail Transfer Bank</label>
                            
                            <input wire:model="bank_name" type="text" class="w-full h-10 bg-white border border-gray-300 rounded px-3 text-sm font-medium focus:ring-1 focus:ring-indigo-500" placeholder="Nama Bank">
                            @error('bank_name') <span class="text-[9px] text-red-600 font-bold block uppercase italic">{{ $message }}</span> @enderror

                            <input wire:model="account_number" type="text" class="w-full h-10 bg-white border border-gray-300 rounded px-3 text-sm font-medium focus:ring-1 focus:ring-indigo-500" placeholder="Nomor Rekening">
                            @error('account_number') <span class="text-[9px] text-red-600 font-bold block uppercase italic">{{ $message }}</span> @enderror

                            <input wire:model="account_holder" type="text" class="w-full h-10 bg-white border border-gray-300 rounded px-3 text-sm font-medium focus:ring-1 focus:ring-indigo-500" placeholder="Nama Lengkap Pemilik Rekening">
                            @error('account_holder') <span class="text-[9px] text-red-600 font-bold block uppercase italic">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full h-10 bg-gray-800 text-white rounded font-bold text-xs uppercase tracking-widest hover:bg-black transition-all mt-4 border border-gray-900 shadow-sm active:translate-y-0.5">
                            Kirim Permintaan
                        </button>
                    </form>
                </div>
            </div>

            <!-- History Section -->
            <div class="lg:col-span-2">
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 italic">Catatan Riwayat (Penarikan)</h2>
                <div class="overflow-x-auto border border-gray-200 rounded">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-50/50">
                            <tr class="text-left">
                                <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-widest">Tanggal / Entri</th>
                                <th class="px-4 py-3 text-[10px] font-black text-gray-500 uppercase tracking-widest">Detail Transfer</th>
                                <th class="px-4 py-3 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Status</th>
                                <th class="px-4 py-3 text-right text-[10px] font-black text-gray-500 uppercase tracking-widest">Nilai Bersih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 italic">
                            @forelse($withdrawals as $w)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <p class="font-bold text-gray-900 text-xs">{{ $w->created_at->format('Y-m-d') }}</p>
                                    <p class="text-[8px] font-normal text-gray-400 uppercase tracking-tighter not-italic">REF: {{ $w->reference_number ?? 'PENDING' }}</p>
                                </td>
                                <td class="px-4 py-4 text-xs text-gray-600">
                                    <span class="font-bold uppercase text-gray-400 text-[9px] not-italic block">{{ $w->bank_info['bank_name'] }}</span>
                                    {{ $w->bank_info['account_number'] }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'approved' => 'bg-green-100 text-green-700 border-green-200',
                                            'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                        ];
                                        $translatedStatus = [
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded text-[9px] font-black uppercase tracking-widest text-white not-italic {{ $w->status === 'approved' ? 'bg-green-600' : ($w->status === 'pending' ? 'bg-yellow-400 text-yellow-900' : 'bg-red-600') }}">
                                        {{ $translatedStatus[$w->status] ?? $w->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <span class="text-sm font-black text-gray-900 tracking-tight">Rp {{ number_format($w->amount, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-gray-400 text-sm italic">Tidak ada catatan riwayat penarikan ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
