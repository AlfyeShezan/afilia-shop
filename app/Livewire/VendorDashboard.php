<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorDashboard extends Component
{
    public $vendor;
    public $stats = [];
    public $recentSales = [];
    public $chartData = [
        'sales' => [],
        'top_products' => []
    ];

    public function mount()
    {
        $this->vendor = Auth::user()->vendor;

        if (!$this->vendor) {
            return redirect()->route('home');
        }

        $this->loadStats();
    }

    public function loadStats()
    {
        // 1. Vendor Stats
        $this->stats = [
            'balance' => $this->vendor->balance,
            'total_orders' => OrderItem::where('vendor_id', $this->vendor->id)->count(),
            'total_earned' => OrderItem::where('vendor_id', $this->vendor->id)
                ->whereHas('order', function($q) {
                    $q->whereIn('status', ['paid', 'shipped', 'completed']);
                })->sum('net_amount'),
        ];

        // 2. Recent Sales Items
        $this->recentSales = OrderItem::where('vendor_id', $this->vendor->id)
            ->with(['order', 'product'])
            ->latest()
            ->limit(5)
            ->get();

        // 3. Chart Data: Daily Sales (Last 7 Days)
        $this->chartData['sales'] = OrderItem::where('vendor_id', $this->vendor->id)
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['paid', 'shipped', 'completed']);
            })
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(net_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->date => $item->total];
            })->toArray();

        // 4. Chart Data: Top Products (Last 30 Days)
        $this->chartData['top_products'] = OrderItem::where('vendor_id', $this->vendor->id)
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->product_name => $item->total_qty];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.vendor-dashboard');
    }
}
