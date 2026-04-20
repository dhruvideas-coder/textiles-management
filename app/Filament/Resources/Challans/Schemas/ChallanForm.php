<?php

namespace App\Filament\Resources\Challans\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\BusinessDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;

class ChallanForm
{
    public static function configure(Schema $schema): Schema
    {
        $isAdmin = Filament::auth()->user()?->role === 'admin';

        $detailFields = [];

        if ($isAdmin) {
            $detailFields[] = Select::make('owner_id')
                ->label('Owner (Business)')
                ->options(
                    User::where('role', 'owner')
                        ->has('businessDetails')
                        ->pluck('name', 'id')
                )
                ->required()
                ->searchable()
                ->live()
                ->afterStateUpdated(function (callable $set) {
                    $set('business_detail_id', null);
                    $set('customer_id', null);
                    $set('product_id', null);
                })
                ->columnSpanFull();

            $detailFields[] = Select::make('business_detail_id')
                ->label('Business Profile')
                ->options(function (callable $get) {
                    $ownerId = $get('owner_id');
                    if (! $ownerId) return [];

                    return BusinessDetail::where('owner_id', $ownerId)
                        ->pluck('business_name', 'id');
                })
                ->placeholder('Select a business profile')
                ->required()
                ->searchable()
                ->hidden(fn ($get) => ! $get('owner_id'))
                ->columnSpanFull();
        } else {
            // Owner / Staff: show their own business profiles
            $authUser = Filament::auth()->user();
            $ownerId  = $authUser?->role === 'owner' ? $authUser->id : $authUser?->owner_id;
            $profiles = BusinessDetail::where('owner_id', $ownerId)->pluck('business_name', 'id');

            if ($profiles->isNotEmpty()) {
                $detailFields[] = Select::make('business_detail_id')
                    ->label('Business Profile')
                    ->options($profiles)
                    ->default($profiles->keys()->first())
                    ->required()
                    ->searchable()
                    ->columnSpanFull();
            }
        }

        $detailFields = array_merge($detailFields, [
            TextInput::make('challan_number')
                ->label('Challan Number')
                ->placeholder('Auto-generated (e.g. 2026-27-0001)')
                ->readOnly()
                ->required(),

            Select::make('customer_id')
                ->label('Party (Customer)')
                ->options(function (callable $get) use ($isAdmin) {
                    $query = Customer::query();
                    if ($isAdmin) {
                        $ownerId = $get('owner_id');
                        if (! $ownerId) return [];
                        $query->where(fn ($q) => $q->where('owner_id', $ownerId)->orWhereNull('owner_id'));
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
                        if (! $ownerId) return [];
                        $query->where(fn ($q) => $q->where('owner_id', $ownerId)->orWhereNull('owner_id'));
                    }
                    return $query->pluck('name', 'id');
                })
                ->required()
                ->searchable()
                ->live()
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
                ->columnSpanFull()
                ->columns([
                    'default' => 1,
                    'sm'      => 2,
                    'lg'      => 3,
                ])
                ->schema($detailFields),

            Section::make('Measurement Grid (6×12)')
                ->columnSpanFull()
                ->schema([
                    ViewField::make('grid_ui')
                        ->columnSpanFull()
                        ->view('filament.forms.challan-grid-wrapper'),
                ]),
        ]);
    }
}
