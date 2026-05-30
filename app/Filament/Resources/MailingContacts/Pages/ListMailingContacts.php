<?php

namespace App\Filament\Resources\MailingContacts\Pages;

use App\Imports\MailingContactsImport;
use App\Filament\Resources\MailingContacts\MailingContactResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListMailingContacts extends ListRecords
{
    protected static string $resource = MailingContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importMailing')
                ->label('Importar XLSX')
                ->form([
                    FileUpload::make('file')
                        ->required()
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->directory('imports'),
                ])
                ->action(function (array $data): void {
                    $path = Storage::disk('local')->path($data['file']);
                    Excel::import(new MailingContactsImport(), $path);

                    Notification::make()
                        ->success()
                        ->title('Importacao concluida')
                        ->body('Planilha de mailing processada com sucesso.')
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
