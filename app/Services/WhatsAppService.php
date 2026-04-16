<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function sendPdf($to, $pdfUrl, $message)
    {
        // Integration with WhatsApp Cloud API
        $token = env('WHATSAPP_TOKEN');
        $phoneId = env('WHATSAPP_PHONE_ID');
        
        if (!$token || !$phoneId) {
            return false;
        }

        $response = Http::withToken($token)->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'document',
            'document' => [
                'link' => $pdfUrl,
                'caption' => $message,
                'filename' => 'Document.pdf'
            ]
        ]);

        return $response->successful();
    }
}
