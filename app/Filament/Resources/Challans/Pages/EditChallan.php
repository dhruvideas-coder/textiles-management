<?php

namespace App\Filament\Resources\Challans\Pages;

use App\Filament\Resources\Challans\ChallanResource;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;
use App\Models\ChallanItem;

class EditChallan extends EditRecord
{
    protected static string $resource = ChallanResource::class;

    public array $gridData = [];

    #[On('grid-updated')]
    public function updateGrid($data, $total_meters, $total_pieces)
    {
        $this->gridData = $data;
        $this->data['total_meters'] = $total_meters;
        $this->data['total_pieces'] = $total_pieces;
    }

    protected function afterSave(): void
    {
        $challanId = $this->record->id;

        // Clear old items and reconstruct to ensure data consistency
        ChallanItem::where('challan_id', $challanId)->delete();

        $items = [];
        foreach ($this->gridData as $row => $cols) {
            foreach ($cols as $col => $meters) {
                if (floatval($meters) > 0) {
                    $items[] = [
                        'challan_id' => $challanId,
                        'row_no'     => $row,
                        'column_no'  => $col,
                        'meters'     => floatval($meters),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (count($items) > 0) {
            ChallanItem::insert($items);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
