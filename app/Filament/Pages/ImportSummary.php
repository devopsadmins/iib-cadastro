<?php

namespace App\Filament\Pages;

use App\Models\ImportRun;
use Filament\Pages\Page;

class ImportSummary extends Page
{
    protected string $view = 'filament.pages.import-summary';

    public static function getNavigationGroup(): ?string
    {
        return 'Relatorios';
    }

    public static function getNavigationLabel(): string
    {
        return 'Resumo importacao';
    }

    public static function getNavigationSort(): ?int
    {
        return 10;
    }

    protected function getViewData(): array
    {
        $latestRun = ImportRun::query()->latest('started_at')->with('fileStats')->first();

        return [
            'latestRun' => $latestRun,
            'stats' => $latestRun?->fileStats ?? collect(),
        ];
    }
}
