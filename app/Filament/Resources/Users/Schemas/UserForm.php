<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\User;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'md' => 2,
            ])
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(191),
                Select::make('role')
                    ->options(function () {
                        if (auth()->user()->role === 'admin') {
                            return [
                                'admin' => 'Admin (Super)',
                                'owner' => 'Owner',
                                'staff' => 'Staff',
                            ];
                        }
                        return [
                            'staff' => 'Staff',
                        ];
                    })
                    ->default(fn () => auth()->user()->role === 'owner' ? 'staff' : 'owner')
                    ->required()
                    ->native(false)
                    ->reactive(),
                Select::make('owner_id')
                    ->label('Owner (for Staff only)')
                    ->options(User::where('role', 'owner')->pluck('name', 'id'))
                    ->nullable()
                    ->searchable()
                    ->hidden(fn ($get) => $get('role') !== 'staff' || auth()->user()->role === 'owner')
                    ->dehydrated(fn ($state) => auth()->user()->role !== 'owner' || $state) // Ensure it's not removed if admin is editing
                    ->default(fn () => auth()->user()->role === 'owner' ? auth()->id() : null),
                TextInput::make('password')
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->dehydrated(fn ($state) => filled($state))
                    ->default('password')
                    ->label('Password'),
            ]);
    }
}
