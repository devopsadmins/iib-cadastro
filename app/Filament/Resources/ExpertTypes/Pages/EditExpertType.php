<?php

namespace App\Filament\Resources\ExpertTypes\Pages;

use App\Filament\Resources\ExpertTypes\ExpertTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExpertType extends EditRecord
{
    protected static string $resource = ExpertTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
