<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Filament\Resources\Bills\BillResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Challan;

class CreateBill extends CreateRecord
{
    protected static string $resource = BillResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (request()->has('challan_id')) {
            $challan = Challan::find(request()->query('challan_id'));
            if ($challan) {
                $data['challan_id'] = $challan->id;
                $data['bill_number'] = $challan->challan_number;
                $data['total_meters'] = $challan->total_meters;
                $rate = $challan->product?->default_rate ?? 0;
                $data['rate'] = $rate;

                $amount = $challan->total_meters * $rate;
                $cgst = $amount * 0.025;
                $sgst = $amount * 0.025;
                $final = $amount + $cgst + $sgst;
                
                $data['amount'] = round($amount, 2);
                $data['cgst_amount'] = round($cgst, 2);
                $data['sgst_amount'] = round($sgst, 2);
                $data['final_total'] = round($final, 2);
            }
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
