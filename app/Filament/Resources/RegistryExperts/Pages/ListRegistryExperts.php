<?php

namespace App\Filament\Resources\RegistryExperts\Pages;

use App\Imports\RegistryExpertsImport;
use App\Filament\Resources\RegistryExperts\RegistryExpertResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListRegistryExperts extends ListRecords
{
    protected static string $resource = RegistryExpertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importExperts')
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
                    Excel::import(new RegistryExpertsImport(), $path);

                    Notification::make()
                        ->success()
                        ->title('Importacao concluida')
                        ->body('Planilha de especialistas processada com sucesso.')
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
