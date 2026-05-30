<?php

namespace App\Filament\Resources\RegistryExperts;

use App\Filament\Resources\RegistryExperts\Pages\CreateRegistryExpert;
use App\Filament\Resources\RegistryExperts\Pages\EditRegistryExpert;
use App\Filament\Resources\RegistryExperts\Pages\ListRegistryExperts;
use App\Filament\Resources\RegistryExperts\Schemas\RegistryExpertForm;
use App\Filament\Resources\RegistryExperts\Tables\RegistryExpertsTable;
use App\Models\RegistryExpert;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RegistryExpertResource extends Resource
{
    protected static ?string $model = RegistryExpert::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationLabel(): string
    {
        return 'Especialistas';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Cadastro';
    }

    public static function getNavigationSort(): ?int
    {
        return 20;
    }

    public static function form(Schema $schema): Schema
    {
        return RegistryExpertForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegistryExpertsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistryExperts::route('/'),
            'create' => CreateRegistryExpert::route('/create'),
            'edit' => EditRegistryExpert::route('/{record}/edit'),
        ];
    }
}
