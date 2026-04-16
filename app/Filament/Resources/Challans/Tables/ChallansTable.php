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
                TextColumn::make('customer.name')
                    ->label('Party')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Quality')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('broker')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge(),
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
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        return redirect()->to('/admin/bills/create?challan_id=' . $record->id);
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
