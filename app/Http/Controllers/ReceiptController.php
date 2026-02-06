<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\Transaction;


class ReceiptController extends Controller
{
       public function downloadReceipt(Order $order, Transaction $transaction)
    {
        if ($order->transaction->payment_method !== 'paystack') {
            abort(403, 'Receipt not available');
        }

        $pdf = Pdf::loadView('pdf.receipt', compact('order','transaction'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Receipt-{$order->receipt_number}.pdf");
    }

}