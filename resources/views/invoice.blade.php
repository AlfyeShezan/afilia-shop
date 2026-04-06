<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ $order->order_number }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #111;
            margin: 0;
            padding: 8mm; /* Adjust for paper margins */
        }
        .content {
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        .header-title {
            font-family: Arial, sans-serif;
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 2px;
        }
        .header-tagline {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #666;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        
        .item-table td {
            vertical-align: top;
            padding: 4px 0;
        }
        
        .summary-table td {
            padding: 2px 0;
        }
        
        .total-row td {
            padding-top: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
        }
        .notice {
            font-style: italic;
            margin-bottom: 10px;
        }
        .timestamp {
            font-size: 9px;
            color: #6b7280;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="text-center">
            <div class="header-title">AFILIA MARKET</div>
            <div class="header-tagline">PREMIUM QUALITY STORE</div>
            <div style="font-size: 10px; margin-bottom: 10px;">
                {{ $order->shipping_name }}<br>
                {{ $order->shipping_city }}<br>
                INV: {{ $order->order_number }}
            </div>
        </div>

        <div class="dashed-line"></div>

        <table class="item-table">
            @foreach($order->items as $item)
            <tr>
                <td colspan="2" class="font-bold uppercase">{{ $item->product_name }}</td>
            </tr>
            <tr>
                <td style="width: 60%;">{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            @if($order->shipping_cost > 0)
            <tr>
                <td colspan="2" class="font-bold">ONGKIR ({{ strtoupper($order->shipping_method ?? 'REGULER') }})</td>
            </tr>
            <tr>
                <td>1 x {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>

        <div class="dashed-line"></div>

        <table class="summary-table">
            <tr>
                <td>SUBTOTAL</td>
                <td class="text-right">{{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>PAJAK (10%)</td>
                <td class="text-right">{{ number_format($order->tax, 0, ',', '.') }}</td>
            </tr>
            @if($order->discount > 0)
            <tr style="color: #000;">
                <td>DISKON</td>
                <td class="text-right">-{{ number_format($order->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td style="border-top: 1px solid #000;">TOTAL AKHIR</td>
                <td class="text-right" style="border-top: 1px solid #000;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="uppercase">BAYAR ({{ str_replace('_', ' ', $order->payment_method) }})</td>
                <td class="text-right">{{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="dashed-line"></div>

        <div class="footer">
            <div class="notice">
                Barang yang sudah dibeli tidak<br>
                dapat ditukar/dikembalikan.
            </div>
            <div class="font-bold">TERIMA KASIH ATAS KUNJUNGAN ANDA</div>
            <div class="timestamp">
                {{ $order->created_at->format('d/m/Y H:i') }} WIB<br>
                www.afiliashop.id
            </div>
        </div>
    </div>
</body>
</html>
