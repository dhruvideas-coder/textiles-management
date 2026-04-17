<?php

namespace App\Http\Controllers;

use App\Models\Challan;
use App\Models\Bill;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function challan($id)
    {
        $challan = Challan::with(['customer', 'product', 'items', 'owner'])->findOrFail($id);

        return Pdf::loadView('pdf.challan', compact('challan'))
            ->setPaper('A4')
            ->download('challan-' . $challan->challan_number . '.pdf');
    }

    public function bill($id)
    {
        $bill = Bill::with(['challan.customer', 'challan.product', 'owner'])->findOrFail($id);

        return Pdf::loadView('pdf.bill', compact('bill'))
            ->setPaper('A4')
            ->download('bill-' . $bill->bill_number . '.pdf');
    }

    public function sendChallanWhatsApp($id, WhatsAppService $service)
    {
        $challan = Challan::with(['customer'])->findOrFail($id);

        $pdfContent = Pdf::loadView('pdf.challan', compact('challan'))
            ->setPaper('A4')
            ->output();

        $path = 'public/pdfs/challan_' . $challan->id . '.pdf';
        Storage::put($path, $pdfContent);

        $url = asset('storage/pdfs/challan_' . $challan->id . '.pdf');

        $phone = $challan->customer?->mobile_number;
        if ($phone) {
            $service->sendPdf($phone, $url, "Here is your Challan #{$challan->challan_number}");
        }

        return back()->with('success', 'WhatsApp sent successfully');
    }
}
