<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public $stats = [];
    public $chartData = [];
    public $lowStockProducts = [];
    public $recentOrders = [];
    public $latestUsers = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // 1. Basic Stats
        $orderStats = Order::where('status', '!=', 'cancelled')
            ->selectRaw('SUM(total) as revenue, COUNT(*) as count')
            ->first();

        $this->stats['revenue'] = $orderStats->revenue ?? 0;
        $this->stats['orders'] = $orderStats->count ?? 0;
        $this->stats['customers'] = User::count();

        // 2. Best Selling Product
        $bestSeller = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->first();
            
        $this->stats['best_seller'] = $bestSeller ? [
            'product_name' => $bestSeller->product_name,
            'total_sold' => $bestSeller->total_sold
        ] : null;

        // 3. 30-Day Sales Trend (Line Chart)
        $salesTrend = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesLabels = [];
        $salesValues = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $salesLabels[] = now()->subDays($i)->format('d M');
            $match = $salesTrend->firstWhere('date', $date);
            $salesValues[] = $match ? (float) $match->total : 0;
        }

        // 4. Popular Categories (Pie Chart)
        $categoryData = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.id', 'categories.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 5. User Growth (Bar Chart - Last 7 Days)
        $userGrowth = User::where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        $userLabels = [];
        $userValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $userLabels[] = now()->subDays($i)->format('d M');
            $match = $userGrowth->firstWhere('date', $date);
            $userValues[] = $match ? (int) $match->count : 0;
        }

        $this->chartData = [
            'sales' => [
                'labels' => array_values($salesLabels),
                'values' => array_values($salesValues),
            ],
            'categories' => [
                'labels' => $categoryData->pluck('name')->toArray(),
                'values' => $categoryData->pluck('total_sold')->map(fn($v) => (float)$v)->toArray(),
            ],
            'users' => [
                'labels' => array_values($userLabels),
                'values' => array_values($userValues),
            ],
        ];

        // 6. Low Stock Alert
        $this->lowStockProducts = Product::where('stock', '<', 10)
            ->where('status', 'active')
            ->orderBy('stock')
            ->take(5)
            ->get();

        // 7. Recent Orders
        $this->recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 8. Latest Users
        $this->latestUsers = User::latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
