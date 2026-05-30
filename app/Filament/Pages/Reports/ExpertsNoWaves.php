<?php

namespace App\Filament\Pages\Reports;

use App\Models\RegistryExpert;
use Filament\Pages\Page;

class ExpertsNoWaves extends Page
{
    protected string $view = 'filament.pages.reports.experts-no-waves';

    public static function getNavigationGroup(): ?string
    {
        return 'Relatorios';
    }

    public static function getNavigationLabel(): string
    {
        return 'Especialistas Sem Vinculo';
    }

    public static function getNavigationSort(): ?int
    {
        return 23;
    }

    protected function getViewData(): array
    {
        $rows = RegistryExpert::query()
            ->with('expertType')
            ->doesntHave('waves')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit(500)
            ->get();

        return ['rows' => $rows];
    }
}
