<?php

namespace App\Filament\Resources\Challans\Pages;

use App\Filament\Resources\Challans\ChallanResource;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\On;
use App\Models\ChallanItem;

class CreateChallan extends CreateRecord
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

    protected function afterCreate(): void
    {
        $challanId = $this->record->id;

        foreach ($this->gridData as $row => $cols) {
            foreach ($cols as $col => $meters) {
                if (floatval($meters) > 0) {
                    ChallanItem::create([
                        'challan_id' => $challanId,
                        'row_no' => $row,
                        'column_no' => $col,
                        'meters' => floatval($meters),
                    ]);
                }
            }
        }
    }
}
