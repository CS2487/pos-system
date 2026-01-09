<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            border: 1px solid #eee;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #000;
        }
        .info {
            margin-bottom: 20px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 10px;
        }
        .info div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            text-align: left;
            border-bottom: 2px solid #333;
            padding: 8px 0;
        }
        [dir="rtl"] table th {
            text-align: right;
        }
        table td {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .totals {
            margin-top: 20px;
        }
        .totals div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .totals .grand-total {
            font-weight: bold;
            font-size: 18px;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body { padding: 0; }
            .receipt-container { border: none; width: 100%; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">{{ __('messages.print') }}</button>
        <button onclick="window.history.back()" style="padding: 10px 20px; cursor: pointer;">{{ __('messages.cancel') }}</button>
    </div>

    <div class="receipt-container">
        <div class="header">
            <h2>{{ config('app.name', 'POS System') }}</h2>
            <p>{{ __('messages.pos_system') }}</p>
        </div>

        <div class="info">
            <div>
                <span>{{ __('messages.order_id') }}:</span>
                <span>#{{ $order->id }}</span>
            </div>
            <div>
                <span>{{ __('messages.date') }}:</span>
                <span>{{ $order->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <div>
                <span>{{ __('messages.cashier') }}:</span>
                <span>{{ $order->user->name }}</span>
            </div>
            @if($order->customer)
            <div>
                <span>{{ __('messages.customers') }}:</span>
                <span>{{ $order->customer->name }}</span>
            </div>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('messages.name') }}</th>
                    <th style="text-align: center;">{{ __('messages.quantity') }}</th>
                    <th style="text-align: right;">{{ __('messages.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div>
                <span>{{ __('messages.subtotal') }}:</span>
                <span>${{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div>
                <span>{{ __('messages.tax') }}:</span>
                <span>${{ number_format($order->tax, 2) }}</span>
            </div>
            @if($order->discount > 0)
            <div>
                <span>{{ __('messages.discount') }}:</span>
                <span>-${{ number_format($order->discount, 2) }}</span>
            </div>
            @endif
            <div class="grand-total">
                <span>{{ __('messages.total') }}:</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
            <div style="margin-top: 10px; font-size: 13px;">
                <span>{{ __('messages.payment_method') }}:</span>
                <span>{{ __('messages.'.$order->payment_method) }}</span>
            </div>
            @if($order->payment_method === 'cash')
            <div style="font-size: 13px;">
                <span>{{ __('messages.received_amount') }}:</span>
                <span>${{ number_format($order->received_amount, 2) }}</span>
            </div>
            <div style="font-size: 13px;">
                <span>{{ __('messages.change_amount') }}:</span>
                <span>${{ number_format($order->change_amount, 2) }}</span>
            </div>
            @endif
        </div>

        <div class="footer">
            <p>{{ __('messages.welcome') }}</p>
            <p>{{ __('messages.copyright') }}</p>
        </div>
    </div>
</body>
</html>
