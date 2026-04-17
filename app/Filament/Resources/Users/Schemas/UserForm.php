<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
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
                        $role = Filament::auth()->user()?->role;
                        if ($role === 'admin') {
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
                    ->default(function () {
                        $role = Filament::auth()->user()?->role;
                        return $role === 'owner' ? 'staff' : 'owner';
                    })
                    ->required()
                    ->native(false)
                    ->live(),
                Select::make('owner_id')
                    ->label('Owner (for Staff only)')
                    ->options(User::where('role', 'owner')->pluck('name', 'id'))
                    ->nullable()
                    ->searchable()
                    ->hidden(fn ($get) => $get('role') !== 'staff' || Filament::auth()->user()?->role === 'owner')
                    ->dehydrated(fn ($state) => Filament::auth()->user()?->role !== 'owner' || $state)
                    ->default(fn () => Filament::auth()->user()?->role === 'owner' ? Filament::auth()->id() : null),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->dehydrated(fn ($state) => filled($state))
                    ->default('password')
                    ->label('Password'),

                Section::make('Business Details')
                    ->description('Appears on Challan and Bill PDF documents.')
                    ->columns(['default' => 1, 'md' => 2])
                    ->hidden(fn ($get) => $get('role') !== 'owner')
                    ->schema([
                        TextInput::make('business_name')
                            ->label('Business / Shop Name')
                            ->maxLength(255),
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
                        Textarea::make('business_address')
                            ->label('Business Address')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('gstin')
                            ->label('GSTIN No.')
                            ->maxLength(15)
                            ->minLength(15)
                            ->rules(['alpha_num', 'size:15'])
                            ->hint('15 characters')
                            ->extraInputAttributes(['style' => 'text-transform: uppercase; letter-spacing: 1px;']),
                    ]),
            ]);
    }
}
