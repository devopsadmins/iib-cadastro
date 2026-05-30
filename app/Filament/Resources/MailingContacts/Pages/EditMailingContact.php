<?php

namespace App\Filament\Resources\MailingContacts\Pages;

use App\Filament\Resources\MailingContacts\MailingContactResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMailingContact extends EditRecord
{
    protected static string $resource = MailingContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
