<?php

namespace App\Filament\Pages\Reports;

use App\Models\RegistryExpert;
use Filament\Pages\Page;

class ExpertsWaveTwo extends Page
{
    protected string $view = 'filament.pages.reports.experts-wave-two';

    public static function getNavigationGroup(): ?string
    {
        return 'Relatorios';
    }

    public static function getNavigationLabel(): string
    {
        return 'Especialistas Onda 2';
    }

    public static function getNavigationSort(): ?int
    {
        return 21;
    }

    protected function getViewData(): array
    {
        $rows = RegistryExpert::query()
            ->with('expertType')
            ->whereHas('waves', fn ($q) => $q->where('wave', 2))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit(500)
            ->get();

        return ['rows' => $rows];
    }
}
