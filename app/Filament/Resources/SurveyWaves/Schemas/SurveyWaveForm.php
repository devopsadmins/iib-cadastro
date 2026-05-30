<?php

namespace App\Filament\Resources\SurveyWaves\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SurveyWaveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->numeric()
                    ->required()
                    ->minValue(2000)
                    ->maxValue(2100),
                TextInput::make('wave')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(2),
                TextInput::make('label')
                    ->maxLength(80),
            ]);
    }
}
