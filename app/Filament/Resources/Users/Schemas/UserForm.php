<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\User;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        $isAdmin = Filament::auth()->user()?->role === 'admin';

        return $schema
            ->columns(['default' => 1, 'md' => 2])
            ->components([
                Section::make('Account Information')
                    ->columns(['default' => 1, 'md' => 2])
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(191),

                        Select::make('role')
                            ->options(function () use ($isAdmin) {
                                if ($isAdmin) {
                                    return [
                                        'admin' => 'Admin (Super)',
                                        'owner' => 'Owner',
                                        'staff' => 'Staff',
                                    ];
                                }
                                return ['staff' => 'Staff'];
                            })
                            ->default(fn () => $isAdmin ? 'owner' : 'staff')
                            ->required()
                            ->native(false)
                            ->live(),

                        Select::make('owner_id')
                            ->label('Assign to Owner')
                            ->options(User::where('role', 'owner')->pluck('name', 'id'))
                            ->nullable()
                            ->searchable()
                            ->hidden(fn ($get) => $get('role') !== 'staff' || ! $isAdmin)
                            ->dehydrated(fn ($state) => ! $isAdmin || $state),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                            ->dehydrated(fn ($state) => filled($state))
                            ->default('password')
                            ->label('Password'),
                    ]),
            ]);
    }
}
