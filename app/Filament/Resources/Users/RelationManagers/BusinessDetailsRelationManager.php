<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BusinessDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'businessDetails';

    protected static ?string $title = 'Business Details';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return auth()->check()
            && auth()->user()->role === 'admin'
            && $ownerRecord->role === 'owner';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'md' => 2])
            ->components([
                TextInput::make('business_name')
                    ->label('Business / Shop Name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('mobile')
                    ->label('Mobile Number')
                    ->tel()
                    ->minLength(10)
                    ->maxLength(10)
                    ->rules(['digits:10'])
                    ->extraInputAttributes([
                        'inputmode'  => 'numeric',
                        'maxlength'  => '10',
                        'onkeypress' => 'return /[0-9]/.test(event.key)',
                        'oninput'    => 'this.value=this.value.replace(/[^0-9]/g,"").slice(0,10)',
                    ]),

                TextInput::make('gstin')
                    ->label('GSTIN No.')
                    ->maxLength(15)
                    ->minLength(15)
                    ->rules(['alpha_num', 'size:15'])
                    ->hint('15 characters')
                    ->extraInputAttributes(['style' => 'text-transform: uppercase; letter-spacing: 1px;']),

                Textarea::make('business_address')
                    ->label('Business Address')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('business_name')
                    ->label('Business Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('mobile')
                    ->label('Mobile')
                    ->placeholder('—'),

                TextColumn::make('gstin')
                    ->label('GSTIN')
                    ->placeholder('—')
                    ->fontFamily('mono'),

                TextColumn::make('business_address')
                    ->label('Address')
                    ->limit(50)
                    ->placeholder('—'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Business')
                    ->icon('heroicon-o-plus'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
