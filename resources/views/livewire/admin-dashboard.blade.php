<div class="p-6 bg-gray-50 min-h-screen space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-200">
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Admin / Dashboard</p>
            <h1 class="text-xl font-semibold text-gray-800">Panel Kontrol</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pemantauan metrik sistem Afilia Market</p>
        </div>
        <button wire:click="loadStats"
            class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 bg-white text-gray-700 text-sm rounded-md hover:bg-gray-100 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Sinkronisasi Data
        </button>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-md p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-3">Total Revenue</p>
            <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-2">+12% vs bulan lalu</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-md p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-3">Total Orders</p>
            <p class="text-2xl font-semibold text-gray-800">{{ number_format($stats['orders']) }}</p>
            <p class="text-xs text-gray-400 mt-2">Processed successfully</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-md p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-3">Active Users</p>
            <p class="text-2xl font-semibold text-gray-800">{{ number_format($stats['customers']) }}</p>
            <p class="text-xs text-gray-400 mt-2">Joined platform</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-md p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-3">Top Product</p>
            <p class="text-sm font-semibold text-gray-800 truncate" title="{{ $stats['best_seller']['product_name'] ?? 'N/A' }}">
                {{ $stats['best_seller']['product_name'] ?? 'N/A' }}
            </p>
            <p class="text-xs text-gray-400 mt-2">{{ $stats['best_seller']['total_sold'] ?? 0 }} unit terjual</p>
        </div>
    </div>

    {{-- ANALYTICS --}}
    <div>
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-4">Analitik & Wawasan</p>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-md p-6">
                <p class="text-sm font-medium text-gray-700 mb-1">Tren Penjualan</p>
                <p class="text-xs text-gray-400 mb-6">Performa 30 hari terakhir</p>
                <div class="h-64" wire:ignore>
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-md p-6">
                <p class="text-sm font-medium text-gray-700 mb-1">Kategori Populer</p>
                <p class="text-xs text-gray-400 mb-6">Penjualan per kategori</p>
                <div class="h-64" wire:ignore>
                    <canvas id="categoryPieChart"></canvas>
                </div>
            </div>
            <div class="lg:col-span-3 bg-white border border-gray-200 rounded-md p-6">
                <p class="text-sm font-medium text-gray-700 mb-1">Pertumbuhan Pengguna</p>
                <p class="text-xs text-gray-400 mb-6">Registrasi baru per hari</p>
                <div class="h-40" wire:ignore>
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- LOW STOCK + RECENT ORDERS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Stok Rendah --}}
        <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                <p class="text-sm font-medium text-gray-700">Stok Rendah</p>
            </div>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3 text-left text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Produk</th>
                        <th class="px-5 py-3 text-center text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Stok</th>
                        <th class="px-5 py-3 text-right text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($lowStockProducts as $p)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="text-sm text-gray-800">{{ $p->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">SKU: {{ $p->sku ?? 'N/A' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="text-xs font-medium text-red-600 bg-red-50 border border-red-100 px-2 py-0.5 rounded">{{ $p->stock }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.products') }}"
                                class="text-xs border border-gray-300 px-3 py-1 rounded-md bg-white hover:bg-gray-100 text-gray-600 transition-colors">Kelola</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-5 py-10 text-center text-sm text-gray-400">Semua stok dalam kondisi normal</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pesanan Terbaru --}}
        <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <p class="text-sm font-medium text-gray-700">Pesanan Terbaru</p>
            </div>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3 text-left text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Order</th>
                        <th class="px-5 py-3 text-left text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Pelanggan</th>
                        <th class="px-5 py-3 text-right text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Total</th>
                        <th class="px-5 py-3 text-center text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.orders') }}?search={{ $order->order_number }}" wire:navigate
                                class="text-xs text-gray-700 hover:text-gray-900 hover:underline font-medium">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-700">{{ $order->user->name ?? 'Tamu' }}</td>
                        <td class="px-5 py-3.5 text-right text-sm text-gray-800 font-medium">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-center">
                            @php
                                $badge = match($order->status) {
                                    'completed'  => 'bg-gray-100 text-gray-700 border-gray-200',
                                    'processing' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'shipped'    => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'cancelled'  => 'bg-red-50 text-red-700 border-red-100',
                                    default      => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                };
                            @endphp
                            <span class="inline-block text-xs px-2 py-0.5 rounded border {{ $badge }} capitalize">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-400">Belum ada pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PELANGGAN TERBARU --}}
    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-sm font-medium text-gray-700">Pelanggan Terbaru</p>
        </div>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-5 py-3 text-left text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Pengguna</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Bergabung</th>
                    <th class="px-5 py-3 text-center text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Role</th>
                    <th class="px-5 py-3 text-right text-xs text-gray-500 uppercase tracking-widest font-medium border-b border-gray-100">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($latestUsers as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5">
                        <p class="text-sm text-gray-800">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="text-xs border border-gray-200 rounded px-2 py-0.5 text-gray-500 bg-gray-50">
                            {{ $user->getRoleNames()->first() ?? 'Pengguna' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <a href="{{ route('admin.users') }}"
                            class="text-xs border border-gray-300 px-3 py-1 rounded-md bg-white hover:bg-gray-100 text-gray-600 transition-colors">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-400">Belum ada pengguna baru</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            const chartData = @json($chartData);

            const initCharts = () => {
                ['salesTrendChart', 'categoryPieChart', 'userGrowthChart'].forEach(id => {
                    const existing = Chart.getChart(id);
                    if (existing) existing.destroy();
                });

                const tooltip = {
                    backgroundColor: '#fff',
                    titleColor: '#111',
                    bodyColor: '#555',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    padding: 10,
                    boxPadding: 4,
                    usePointStyle: true,
                    bodyFont: { family: 'Figtree', size: 11 },
                    titleFont: { family: 'Figtree', size: 12, weight: '600' }
                };

                // Sales Trend
                const salesCtx = document.getElementById('salesTrendChart');
                if (salesCtx) {
                    const gradient = salesCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.1)');
                    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

                    new Chart(salesCtx, {
                        type: 'line',
                        data: {
                            labels: chartData.sales.labels,
                            datasets: [{
                                data: chartData.sales.values,
                                borderColor: '#3b82f6',
                                backgroundColor: gradient,
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 2,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#3b82f6',
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false }, tooltip },
                            scales: {
                                x: { 
                                    grid: { display: false }, 
                                    ticks: { 
                                        font: { size: 10, family: 'Figtree' }, 
                                        color: '#9ca3af',
                                        maxRotation: 0,
                                        autoSkip: true,
                                        maxTicksLimit: 7
                                    } 
                                },
                                y: { 
                                    beginAtZero: true, 
                                    grid: { color: '#f3f4f6', drawBorder: false }, 
                                    ticks: { 
                                        font: { size: 10, family: 'Figtree' }, 
                                        color: '#9ca3af',
                                        callback: function(value) {
                                            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'jt';
                                            if (value >= 1000) return (value / 1000).toFixed(0) + 'rb';
                                            return value;
                                        }
                                    } 
                                }
                            }
                        }
                    });
                }

                // Category Pie
                const catCtx = document.getElementById('categoryPieChart');
                if (catCtx) {
                    new Chart(catCtx, {
                        type: 'doughnut',
                        data: {
                            labels: chartData.categories.labels,
                            datasets: [{
                                data: chartData.categories.values,
                                backgroundColor: [
                                    '#6366f1', // Indigo 500
                                    '#0ea5e9', // Sky 500
                                    '#f43f5e', // Rose 500
                                    '#8b5cf6', // Violet 500
                                    '#10b981'  // Emerald 500
                                ],
                                borderWidth: 4,
                                borderColor: '#fff',
                                hoverOffset: 8,
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: { 
                                        padding: 24, 
                                        usePointStyle: true, 
                                        pointStyle: 'circle', 
                                        font: { size: 10, family: 'Figtree', weight: '500' }, 
                                        color: '#64748b' 
                                    }
                                },
                                tooltip: {
                                    ...tooltip,
                                    callbacks: {
                                        label: (context) => {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return ` ${label}: ${value} unit (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // User Growth
                const userCtx = document.getElementById('userGrowthChart');
                if (userCtx) {
                    new Chart(userCtx, {
                        type: 'bar',
                        data: {
                            labels: chartData.users.labels,
                            datasets: [{
                                data: chartData.users.values,
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                hoverBackgroundColor: '#3b82f6',
                                borderRadius: 4,
                                barThickness: 'flex',
                                maxBarThickness: 32
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false }, tooltip },
                            scales: {
                                x: { grid: { display: false }, ticks: { font: { size: 10, family: 'Figtree' }, color: '#9ca3af' } },
                                y: { 
                                    beginAtZero: true, 
                                    grid: { color: '#f3f4f6', drawBorder: false }, 
                                    ticks: { stepSize: 1, font: { size: 10, family: 'Figtree' }, color: '#9ca3af' } 
                                }
                            }
                        }
                    });
                }
            };

            if (window.Livewire) {
                initCharts();
            } else {
                document.addEventListener('livewire:initialized', initCharts);
            }
        })();
    </script>
</div>
