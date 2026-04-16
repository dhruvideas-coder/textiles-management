<?php

namespace App\Filament\Resources\Challans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;

class ChallanForm
{
    public static function configure(Schema $schema): Schema
    {
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';

        $detailFields = [];

        // Admin picks which owner's data to use
        if ($isAdmin) {
            $detailFields[] = Select::make('owner_id')
                ->label('Owner (Business)')
                ->options(User::where('role', 'owner')->pluck('name', 'id'))
                ->required()
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function (callable $set) {
                    $set('customer_id', null);
                    $set('product_id', null);
                })
                ->columnSpanFull();
        }

        $detailFields = array_merge($detailFields, [
            TextInput::make('challan_number')
                ->required()
                ->maxLength(255),

            Select::make('customer_id')
                ->label('Party (Customer)')
                ->options(function (callable $get) use ($isAdmin) {
                    $query = Customer::query();
                    if ($isAdmin) {
                        $ownerId = $get('owner_id');
                        if (!$ownerId) return [];
                        $query->where(fn($q) => $q->where('owner_id', $ownerId)->orWhereNull('owner_id'));
                    }
                    return $query->pluck('name', 'id');
                })
                ->required()
                ->searchable(),

            Select::make('product_id')
                ->label('Quality (Product)')
                ->options(function (callable $get) use ($isAdmin) {
                    $query = Product::query();
                    if ($isAdmin) {
                        $ownerId = $get('owner_id');
                        if (!$ownerId) return [];
                        $query->where(fn($q) => $q->where('owner_id', $ownerId)->orWhereNull('owner_id'));
                    }
                    return $query->pluck('name', 'id');
                })
                ->required()
                ->searchable()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => $set('rate', Product::find($state)?->default_rate ?? 0)),

            TextInput::make('broker')
                ->maxLength(255),

            DatePicker::make('date')
                ->default(now())
                ->required(),

            Select::make('status')
                ->options([
                    'At Mill'       => 'At Mill',
                    'Process House' => 'Process House',
                    'In Stock'      => 'In Stock',
                    'Billed'        => 'Billed',
                ])
                ->default('At Mill')
                ->required(),

            TextInput::make('total_pieces')
                ->required()
                ->numeric()
                ->readOnly()
                ->default(0),

            TextInput::make('total_meters')
                ->required()
                ->numeric()
                ->readOnly()
                ->default(0.0),
        ]);

        return $schema->components([
            Section::make('Challan Details')
                ->columns([
                    'default' => 1,
                    'sm' => 2,
                    'lg' => 3,
                ])
                ->schema($detailFields),

            Section::make('Measurement Grid (6×12)')
                ->schema([
                    ViewField::make('grid_ui')
                        ->view('filament.forms.challan-grid-wrapper'),
                ]),
        ]);
    }
}
