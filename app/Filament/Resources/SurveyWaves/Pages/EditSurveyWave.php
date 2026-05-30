<?php

namespace App\Filament\Resources\SurveyWaves\Pages;

use App\Filament\Resources\SurveyWaves\SurveyWaveResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSurveyWave extends EditRecord
{
    protected static string $resource = SurveyWaveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
