<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        .header, .bill-to, .items, .totals, .footer {
            margin-bottom: 30px;
        }
        .flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .text-right {
            text-align: right;
        }
        h1 {
            font-size: 28px;
            color: #1E3A8A; /* Brand color */
            margin: 0;
        }
        h2, h3 {
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f3f4f6;
            text-align: left;
        }
        .totals {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .totals div {
            width: 300px;
        }
        .totals .line {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
        }
        .totals .total {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
            padding-top: 10px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
        }
        .logo {
            height: 50px;
            margin-bottom: 10px;
        }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="<?php echo public_path('images/logo/logo.png'); ?>" alt="Logo" class="logo">
        <h1>Invoice</h1>
        <p>Invoice Number: {{ $order->id }}</p>
        <p>Date: {{ $order->created_at->format('d/m/Y') }}</p>
    </div>
    <div class="bill-to">
        <h2>Billed To:</h2>
        <p>{{ $order->address->full_name}}</p>
        <p>{{ $order->address->street }}, {{ $order->address->lga }}, {{ $order->state->name }}</p>
        <p>{{ $order->user->email }}</p>
    </div>
    <div class="items">
        <table>
            <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">₦{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">₦{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="totals">
        <div>
                <div class="line"><span>Subtotal:</span> <span>N{{ number_format($order->subtotal, 2) }}</span></div>
                <div class="line"><span>Shipping:</span> <span>N{{ number_format($order->shipping_price, 2) }}</span></div>
                <!-- <div class="line"><span>Discount:</span> <span>-N{{ number_format($order->discount, 2) }}</span></div> -->
                <div class="line"><span>Tax:</span> <span>₦{{ number_format($order->tax, 2) }}</span></div>
                <!-- <div class="line total"><span>Total Discount:</span> <span>-N{{ number_format($order->total_discount, 2) }}</span></div> -->
                <div class="line total"><span>Total:</span> <span>₦{{ number_format($order->total, 2) }}</span></div>
        </div>
    </div>
    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
</div>
</body>
</html>
