<?php

namespace App\Filament\Resources\Bills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bill_number')
                    ->label('Bill No.')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('challan.challan_number')
                    ->label('Challan No.')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('challan.customer.name')
                    ->label('Party')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_meters')
                    ->label('Meters')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rate')
                    ->label('Rate')
                    ->money('INR')
                    ->sortable(),
                TextColumn::make('final_total')
                    ->label('Total (₹)')
                    ->money('INR')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn ($record) => route('pdf.bill', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
