<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Payment Receipt</title>
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
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
            text-align: center;
        }
        h1 {
            color: #16a34a; /* green */
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            margin: 5px 0;
        }
        .logo {
            height: 50px;
            margin-bottom: 15px;
        }
        .details {
            text-align: left;
            margin: 20px 0;
        }
        .details p {
            margin: 6px 0;
        }
        .total {
            margin-top: 20px;
            font-weight: bold;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            border-top: 2px solid #333;
            padding-top: 10px;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #888;
        }
</style>
</head>
<body>
    <div class="container">
        <img src="<?php echo public_path('images/logo/logo.png'); ?>" alt="Logo" class="logo">
        <h1>Payment Receipt</h1>
        <div class="details">
            <p><strong>Order ID:</strong> {{ $order->id }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
            <p><strong>Customer Name:</strong> {{ $order->address->full_name}}</p>
            <p><strong>Customer Email:</strong> {{ $order->user->email }}</p>
            <p><strong>Customer Phone:</strong> {{ $order->address->phone }}</p>
           
        </div>
        <div class="section">
            <h3>AMOUNT PAID</h3>
            <span>₦ {{ number_format($order->total, 2) }} </span> <!-- Using 'N' as currency symbol -->
        </div>
        <div class="total">
            <span>Total:</span>
            <span>₦ {{ number_format($order->total, 2) }} </span> <!-- Using Unicode naira symbol -->
        </div>
        <div class="footer">
            <p>Thank you for your purchase!</p>
        </div>
    </div>
</body>
</html>
