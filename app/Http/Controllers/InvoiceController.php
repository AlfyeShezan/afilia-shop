<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function show(string $orderNumber)
    {
        $order = Order::with(['items.product.images', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Only the order owner or admin can view the invoice
        if (Auth::id() !== $order->user_id && !Auth::user()->hasRole(['super-admin', 'admin', 'staff'])) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        $pdf = Pdf::loadView('invoice', compact('order'));
        
        // Calculate dynamic height
        $itemCount = $order->items->count();
        $hasShipping = $order->shipping_cost > 0;
        $hasDiscount = $order->discount > 0;
        
        // Base height (header, padding, summary base, footer) in pt
        // 80mm width is 226.77pt
        $baseHeight = 220; 
        $rowHeight = 35; // Per item row
        $summaryHeight = 100; // Summary section
        if ($hasShipping) $baseHeight += $rowHeight;
        if ($hasDiscount) $summaryHeight += 15;
        
        $totalHeight = $baseHeight + ($itemCount * $rowHeight) + $summaryHeight;
        
        // Set paper size: [x, y, width, height]
        $pdf->setPaper([0, 0, 226.77, $totalHeight], 'portrait');
        
        return $pdf->download('Invoice-' . $order->order_number . '.pdf');
    }
}
