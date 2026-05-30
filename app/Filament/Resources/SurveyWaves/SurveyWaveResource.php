<?php

namespace App\Filament\Resources\SurveyWaves;

use App\Filament\Resources\SurveyWaves\Pages\CreateSurveyWave;
use App\Filament\Resources\SurveyWaves\Pages\EditSurveyWave;
use App\Filament\Resources\SurveyWaves\Pages\ListSurveyWaves;
use App\Filament\Resources\SurveyWaves\Schemas\SurveyWaveForm;
use App\Filament\Resources\SurveyWaves\Tables\SurveyWavesTable;
use App\Models\SurveyWave;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurveyWaveResource extends Resource
{
    protected static ?string $model = SurveyWave::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationLabel(): string
    {
        return 'Ondas';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Cadastro';
    }

    public static function getNavigationSort(): ?int
    {
        return 40;
    }

    public static function form(Schema $schema): Schema
    {
        return SurveyWaveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveyWavesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSurveyWaves::route('/'),
            'create' => CreateSurveyWave::route('/create'),
            'edit' => EditSurveyWave::route('/{record}/edit'),
        ];
    }
}
