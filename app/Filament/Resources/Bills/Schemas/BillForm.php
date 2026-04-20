<?php

namespace App\Filament\Resources\Bills\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\BusinessDetail;
use App\Models\Challan;
use App\Models\User;

class BillForm
{
    public static function configure(Schema $schema): Schema
    {
        $isAdmin = Filament::auth()->user()?->role === 'admin';

        $fields = [];

        if ($isAdmin) {
            $fields[] = Select::make('owner_id')
                ->label('Owner (Business)')
                ->options(
                    User::where('role', 'owner')
                        ->has('businessDetails')
                        ->pluck('name', 'id')
                )
                ->required()
                ->searchable()
                ->live()
                ->afterStateUpdated(fn (callable $set) => $set('challan_id', null))
                ->columnSpanFull();
        }

        $fields = array_merge($fields, array_filter([
            Select::make('challan_id')
                ->label('Challan')
                ->options(function (callable $get) use ($isAdmin) {
                    $query = Challan::query()->doesntHave('bill');
                    if ($isAdmin) {
                        $ownerId = $get('owner_id');
                        if (! $ownerId) return [];
                        $query->where('owner_id', $ownerId);
                    }
                    return $query->pluck('challan_number', 'id');
                })
                ->required()
                ->searchable()
                ->live()
                ->afterStateUpdated(function ($state, callable $set) {
                    $challan = Challan::find($state);
                    if ($challan) {
                        $set('bill_number', $challan->challan_number);
                        $set('total_meters', $challan->total_meters);
                        $rate = $challan->product?->default_rate ?? 0;
                        $set('rate', $rate);
                        self::calculateTotals($set, $challan->total_meters, $rate);
                    }
                }),

            TextInput::make('bill_number')
                ->required(),

            TextInput::make('total_meters')
                ->required()
                ->numeric()
                ->readonly()
                ->default(0.0),

            TextInput::make('rate')
                ->required()
                ->numeric()
                ->live()
                ->default(0.0)
                ->afterStateUpdated(fn ($state, callable $get, callable $set) => self::calculateTotals($set, $get('total_meters'), floatval($state))),

            TextInput::make('amount')
                ->required()
                ->numeric()
                ->readonly()
                ->default(0.0),

            TextInput::make('cgst_amount')
                ->label('CGST (2.5%)')
                ->required()
                ->numeric()
                ->readonly()
                ->default(0.0),

            TextInput::make('sgst_amount')
                ->label('SGST (2.5%)')
                ->required()
                ->numeric()
                ->readonly()
                ->default(0.0),

            TextInput::make('final_total')
                ->required()
                ->numeric()
                ->readonly()
                ->default(0.0)
                ->columnSpanFull(),
        ]));

        return $schema
            ->columns(['default' => 1, 'md' => 2, 'lg' => 3])
            ->components($fields);
    }

    public static function calculateTotals(callable $set, $meters, $rate): void
    {
        $amount = floatval($meters) * floatval($rate);
        $cgst   = $amount * 0.025;
        $sgst   = $amount * 0.025;
        $final  = $amount + $cgst + $sgst;

        $set('amount',      round($amount, 2));
        $set('cgst_amount', round($cgst,   2));
        $set('sgst_amount', round($sgst,   2));
        $set('final_total', round($final,  2));
    }
}
