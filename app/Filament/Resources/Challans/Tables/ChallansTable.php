<?php

namespace App\Filament\Resources\Challans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Bill;

class ChallansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('challan_number')
                    ->label('No.')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('businessDetail.business_name')
                    ->label('Business Profile')
                    ->searchable()
                    ->placeholder('—')
                    ->badge()
                    ->color('info'),
                TextColumn::make('customer.name')
                    ->label('Party')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Quality')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('broker')
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_pieces')
                    ->label('Pcs')
                    ->numeric()
                    ->sortable()
                    ->hiddenFrom('md'),
                TextColumn::make('total_meters')
                    ->label('Mtrs')
                    ->numeric()
                    ->sortable()
                    ->weight('bold'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                Action::make('download_challan_pdf')
                    ->label('Challan PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn ($record) => route('pdf.challan', $record))
                    ->openUrlInNewTab(),
                Action::make('generate_bill')
                    ->label('Generate Bill')
                    ->icon('heroicon-o-banknotes')
                    ->button()
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        return redirect()->to(\App\Filament\Resources\Bills\BillResource::getUrl('create', ['challan_id' => $record->id]));
                    })
                    ->hidden(fn ($record) => $record->bill()->exists()),
                Action::make('download_bill_pdf')
                    ->label('Bill PDF')
                    ->icon('heroicon-o-document-currency-rupee')
                    ->url(fn ($record) => route('pdf.bill', $record->bill->id))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->bill()->exists()),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
