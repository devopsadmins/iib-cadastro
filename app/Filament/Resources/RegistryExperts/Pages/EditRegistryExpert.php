<?php

namespace App\Filament\Resources\RegistryExperts\Pages;

use App\Filament\Resources\RegistryExperts\RegistryExpertResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRegistryExpert extends EditRecord
{
    protected static string $resource = RegistryExpertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
