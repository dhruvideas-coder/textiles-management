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
    
    public function mount(): void
    {
        parent::mount();
        
        $this->form->fill([
            'challan_number' => $this->getNextChallanNumber(),
            'date' => now(),
            'status' => 'At Mill',
        ]);
    }

    public function getNextChallanNumber(?int $ownerId = null): string
    {
        $user = auth()->user();
        $ownerId = $ownerId ?? ($user->role === 'owner' ? $user->id : $user->owner_id);
        
        if (!$ownerId) return '';

        $year = date('Y');
        $nextYearSuffix = date('y', strtotime('+1 year'));
        $prefix = "{$year}-{$nextYearSuffix}-";

        $lastChallan = \App\Models\Challan::withoutGlobalScopes()
            ->where('owner_id', $ownerId)
            ->where('challan_number', 'like', $prefix . '%')
            ->orderBy('challan_number', 'desc')
            ->first();

        if ($lastChallan) {
            $lastNumber = intval(substr($lastChallan->challan_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Re-generate before create to prevent duplicates in high-concurrency
        $data['challan_number'] = $this->getNextChallanNumber($data['owner_id'] ?? null);
        return $data;
    }

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
