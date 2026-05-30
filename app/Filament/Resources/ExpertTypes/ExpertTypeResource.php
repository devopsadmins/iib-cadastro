<?php

namespace App\Filament\Resources\ExpertTypes;

use App\Filament\Resources\ExpertTypes\Pages\CreateExpertType;
use App\Filament\Resources\ExpertTypes\Pages\EditExpertType;
use App\Filament\Resources\ExpertTypes\Pages\ListExpertTypes;
use App\Filament\Resources\ExpertTypes\Schemas\ExpertTypeForm;
use App\Filament\Resources\ExpertTypes\Tables\ExpertTypesTable;
use App\Models\ExpertType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExpertTypeResource extends Resource
{
    protected static ?string $model = ExpertType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationLabel(): string
    {
        return 'Tipos';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Cadastro';
    }

    public static function getNavigationSort(): ?int
    {
        return 10;
    }

    public static function form(Schema $schema): Schema
    {
        return ExpertTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpertTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpertTypes::route('/'),
            'create' => CreateExpertType::route('/create'),
            'edit' => EditExpertType::route('/{record}/edit'),
        ];
    }
}
