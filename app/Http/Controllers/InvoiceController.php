<?php
namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;

class InvoiceController extends Controller
{
    
    public function downloadInvoice(Order $order)
    {
        $order->load(['items.product', 'address']);

        $pdf = Pdf::loadView('pdf.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Invoice-{$order->invoice_number}.pdf");
    }

}
