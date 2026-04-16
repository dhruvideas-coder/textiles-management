<?php

namespace App\Filament\Resources\Challans;

use App\Filament\Resources\Challans\Pages\CreateChallan;
use App\Filament\Resources\Challans\Pages\EditChallan;
use App\Filament\Resources\Challans\Pages\ListChallans;
use App\Filament\Resources\Challans\Schemas\ChallanForm;
use App\Filament\Resources\Challans\Tables\ChallansTable;
use App\Models\Challan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChallanResource extends Resource
{
    protected static ?string $model = Challan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Documents';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Challans';

    protected static ?string $recordTitleAttribute = 'challan_number';

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->check() && auth()->user()->role !== 'staff';
    }

    public static function form(Schema $schema): Schema
    {
        return ChallanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChallansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListChallans::route('/'),
            'create' => CreateChallan::route('/create'),
            'edit'   => EditChallan::route('/{record}/edit'),
        ];
    }
}
