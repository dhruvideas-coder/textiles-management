<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\User;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';

        return $schema
            ->columns([
                'default' => 1,
                'md' => 2,
            ])
            ->components(array_filter([


                TextInput::make('name')
                    ->label('Quality Name')
                    ->required()
                    ->placeholder('e.g. PXP Vichitra, Dola Silk')
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('default_rate')
                    ->label('Default Rate (₹/m)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0.0),

                TextInput::make('last_used_rate')
                    ->label('Last Used Rate (₹/m)')
                    ->numeric()
                    ->minValue(0)
                    ->readOnly()
                    ->default(0.0),
            ]));
    }
}
