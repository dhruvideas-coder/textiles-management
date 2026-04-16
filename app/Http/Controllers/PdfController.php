<?php

namespace App\Http\Controllers;

use App\Models\Challan;
use App\Models\Bill;
use App\Services\WhatsAppService;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function challan($id)
    {
        putenv('TMPDIR=' . storage_path('app/temp'));
        putenv('TEMP=' . storage_path('app/temp'));
        putenv('TMP=' . storage_path('app/temp'));
        $challan = Challan::with(['customer', 'product', 'items', 'owner'])->findOrFail($id);

        $html = view('pdf.challan', compact('challan'))->render();
        
        return response(
            Browsershot::html($html)
                ->setNodeBinary('C:\nvm4w\nodejs\node.exe')
                ->setNpmBinary('C:\nvm4w\nodejs\npm.cmd')
                ->setIncludePath(base_path())
                ->setChromePath('C:\Users\Dhruv\.cache\puppeteer\chrome\win64-147.0.7727.56\chrome-win64\chrome.exe')
                ->addChromiumArguments(['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage', '--disable-extensions'])
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->showBackground()
                ->pdf()
        )->header('Content-Type', 'application/pdf');
    }

    public function bill($id)
    {
        putenv('TMPDIR=' . storage_path('app/temp'));
        putenv('TEMP=' . storage_path('app/temp'));
        putenv('TMP=' . storage_path('app/temp'));
        $bill = Bill::with(['challan.customer', 'challan.product', 'owner'])->findOrFail($id);

        $html = view('pdf.bill', compact('bill'))->render();
        
        return response(
            Browsershot::html($html)
                ->setNodeBinary('C:\nvm4w\nodejs\node.exe')
                ->setNpmBinary('C:\nvm4w\nodejs\npm.cmd')
                ->setIncludePath(base_path())
                ->setChromePath('C:\Users\Dhruv\.cache\puppeteer\chrome\win64-147.0.7727.56\chrome-win64\chrome.exe')
                ->addChromiumArguments(['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage', '--disable-extensions'])
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->showBackground()
                ->pdf()
        )->header('Content-Type', 'application/pdf');
    }

    public function sendChallanWhatsApp($id, WhatsAppService $service)
    {
        putenv('TMPDIR=' . storage_path('app/temp'));
        putenv('TEMP=' . storage_path('app/temp'));
        putenv('TMP=' . storage_path('app/temp'));
        $challan = Challan::with(['customer'])->findOrFail($id);
        
        // Generate PDF and store it locally
        $html = view('pdf.challan', compact('challan'))->render();
        $pdfContent = Browsershot::html($html)
            ->setNodeBinary('C:\nvm4w\nodejs\node.exe')
            ->setNpmBinary('C:\nvm4w\nodejs\npm.cmd')
            ->setIncludePath(base_path())
            ->setChromePath('C:\Users\Dhruv\.cache\puppeteer\chrome\win64-147.0.7727.56\chrome-win64\chrome.exe')
            ->addChromiumArguments(['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage', '--disable-extensions'])
            ->format('A4')
            ->pdf();
        
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
