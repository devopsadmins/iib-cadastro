<?php

namespace App\Filament\Resources\SurveyWaves\Pages;

use App\Filament\Resources\SurveyWaves\SurveyWaveResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSurveyWaves extends ListRecords
{
    protected static string $resource = SurveyWaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
