    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="mb-8 flex justify-between items-center border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">Pusat Kontrol Vendor</h1>
            <p class="text-sm text-gray-500 mt-1">Ikhtisar status untuk <span class="font-bold underline">{{ $vendor->name }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded text-xs font-bold text-gray-700 hover:bg-gray-200 transition-all">
                KELOLA PRODUK
            </a>
            <a href="{{ route('vendor.withdrawal') }}" class="px-4 py-2 bg-indigo-600 border border-indigo-700 rounded text-xs font-bold text-white hover:bg-indigo-700 transition-all">
                TARIK DANA
            </a>
        </div>
    </div>

    <!-- Analytics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
        <!-- Sales Trend -->
        <div class="bg-gray-50/50 p-8 rounded-[32px] border border-gray-100 shadow-sm" x-data="{
            init() {
                const ctx = document.getElementById('salesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @js(array_keys($chartData['sales'])),
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: @js(array_values($chartData['sales'])),
                            borderColor: '#4f46e5',
                            backgroundColor: '#4f46e522',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#4f46e5'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true, grid: { display: false }, ticks: { font: { size: 10 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                        }
                    }
                });
            }
        }">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400">Tren Penjualan (7 Hari)</h3>
                <span class="text-indigo-600 text-[10px] font-bold">Rp</span>
            </div>
            <div class="h-64" wire:ignore>
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-gray-50/50 p-8 rounded-[32px] border border-gray-100 shadow-sm" x-data="{
            init() {
                const ctx = document.getElementById('productsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @js(array_keys($chartData['top_products'])),
                        datasets: [{
                            label: 'Jumlah Terjual',
                            data: @js(array_values($chartData['top_products'])),
                            backgroundColor: '#0f172a',
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: { beginAtZero: true, grid: { display: false }, ticks: { font: { size: 10 } } },
                            y: { grid: { display: false }, ticks: { font: { size: 10 } } }
                        }
                    }
                });
            }
        }">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400">Produk Terlaris (30 Hari)</h3>
                <span class="text-slate-900 text-[10px] font-bold">Qty</span>
            </div>
            <div class="h-64" wire:ignore>
                <canvas id="productsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Account Summary Table -->
        <div class="lg:col-span-1">
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 italic">Ringkasan Akun</h2>
            <table class="w-full border-collapse border border-gray-200">
                <tbody>
                    <tr>
                        <th class="bg-gray-50 border border-gray-200 px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-tighter">Saldo Saat Ini</th>
                        <td class="border border-gray-200 px-4 py-3 font-black text-xl text-indigo-700">Rp {{ number_format($stats['balance'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-gray-50 border border-gray-200 px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-tighter">Total Pendapatan</th>
                        <td class="border border-gray-200 px-4 py-3 font-bold text-gray-700">Rp {{ number_format($stats['total_earned'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-gray-50 border border-gray-200 px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-tighter">Volume Penjualan</th>
                        <td class="border border-gray-200 px-4 py-3 font-bold text-gray-900">{{ $stats['total_orders'] }} Pesanan</td>
                    </tr>
                    <tr>
                        <th class="bg-gray-50 border border-gray-200 px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-tighter">Logika Komisi</th>
                        <td class="border border-gray-200 px-4 py-3 font-bold text-gray-400 italic">Biaya Platform {{ $vendor->commission_rate }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Sales Ledger Table -->
        <div class="lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest italic">Buku Penjualan Terbaru</h2>
                <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 uppercase">Sistem Aktif</span>
            </div>
            <div class="overflow-x-auto border border-gray-200 rounded">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border-b border-gray-200 px-4 py-3 text-left text-[10px] font-black text-gray-500 uppercase">Deskripsi Produk</th>
                            <th class="border-b border-gray-200 px-4 py-3 text-center text-[10px] font-black text-gray-500 uppercase">Jumlah</th>
                            <th class="border-b border-gray-200 px-4 py-3 text-center text-[10px] font-black text-gray-500 uppercase">Waktu</th>
                            <th class="border-b border-gray-200 px-4 py-3 text-right text-[10px] font-black text-gray-500 uppercase">Laba Bersih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 italic">
                        @forelse($recentSales as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <span class="text-sm font-bold text-gray-900">{{ $item->product_name }}</span>
                            </td>
                            <td class="px-4 py-4 text-center font-bold text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-4 py-4 text-center text-xs text-gray-400 not-italic">{{ $item->created_at->format('d M, H:i') }}</td>
                            <td class="px-4 py-4 text-right">
                                <span class="text-sm font-black text-emerald-600 underline">Rp {{ number_format($item->net_amount, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-gray-400 text-sm italic">Tidak ada aktivitas penjualan yang dicatat saat ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($recentSales->count() > 0)
            <div class="mt-4 flex justify-end">
                <button class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-indigo-600 underline transition-all italic">
                    Buat Laporan Keuangan Lengkap
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
