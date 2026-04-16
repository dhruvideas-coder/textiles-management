<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\User;

class CustomerForm
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
                // Admin must pick an owner; owner/staff get it auto-assigned


                TextInput::make('name')
                    ->label('Customer / Party Name')
                    ->required()
                    ->placeholder('e.g. Raj Textiles')
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('mobile_number')
                    ->label('Mobile Number')
                    ->tel()
                    ->maxLength(15),

                TextInput::make('GSTIN')
                    ->label('GSTIN')
                    ->maxLength(15)
                    ->placeholder('24AAAAA0000A1Z5'),

                Textarea::make('address')
                    ->label('Address')
                    ->rows(3)
                    ->columnSpanFull(),
            ]));
    }
}
