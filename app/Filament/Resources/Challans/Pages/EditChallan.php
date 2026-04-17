<?php

namespace App\Filament\Resources\Challans\Pages;

use App\Filament\Resources\Challans\ChallanResource;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;
use App\Models\ChallanItem;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;

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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate_bill')
                ->label('Generate Bill')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->requiresConfirmation()
                ->action(function ($record) {
                    return redirect()->to(\App\Filament\Resources\Bills\BillResource::getUrl('create', ['challan_id' => $record->id]));
                })
                ->hidden(fn ($record) => $record->bill()->exists()),
            DeleteAction::make(),
        ];
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

        // Delete associated bill if it exists when owner edits
        if ($this->record->bill) {
            $this->record->bill->delete();
            
            if ($this->record->status === 'Billed') {
                $this->record->update(['status' => 'In Stock']);
            }

            Notification::make()
                ->title('Associated bill removed')
                ->body('Since the challan was modified, the existing bill has been deleted. Please generate a new bill.')
                ->warning()
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
