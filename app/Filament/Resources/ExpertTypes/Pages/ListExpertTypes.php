<?php

namespace App\Filament\Resources\ExpertTypes\Pages;

use App\Filament\Resources\ExpertTypes\ExpertTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpertTypes extends ListRecords
{
    protected static string $resource = ExpertTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
