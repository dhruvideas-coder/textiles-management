<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        $isAdmin = auth()->user()?->role === 'admin';

        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->email),

                BadgeColumn::make('role')
                    ->color(fn ($state) => match ($state) {
                        'admin' => 'danger',
                        'owner' => 'warning',
                        'staff' => 'success',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->hidden(! $isAdmin),

                TextColumn::make('owner.name')
                    ->label('Owner')
                    ->sortable()
                    ->placeholder('—')
                    ->hidden(fn () => ! $isAdmin),

                TextColumn::make('businessDetails.business_name')
                    ->label('Business Profiles')
                    ->badge()
                    ->color('info')
                    ->separator(',')
                    ->placeholder('No profiles yet')
                    ->wrap()
                    ->hidden(fn () => ! $isAdmin),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->date('d M Y')
                    ->sortable()
                    ->hidden(fn () => ! $isAdmin),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'owner' => 'Owner',
                        'staff' => 'Staff',
                    ])
                    ->placeholder('All Roles')
                    ->hidden(! $isAdmin),
            ])
            ->actions([
                EditAction::make()
                    ->hidden(fn () => ! $isAdmin),
                ViewAction::make()
                    ->visible(fn () => ! $isAdmin),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->hidden(fn () => ! $isAdmin),
                ]),
            ]);
    }
}
