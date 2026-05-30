<?php

namespace App\Filament\Pages\Reports;

use App\Models\RegistryExpert;
use Filament\Pages\Page;

class ExpertsBothWaves extends Page
{
    protected string $view = 'filament.pages.reports.experts-both-waves';

    protected static \UnitEnum|string|null $navigationGroup = 'Relatorios';

    protected static ?string $navigationLabel = 'Especialistas Ambas Ondas';

    protected static ?int $navigationSort = 22;

    protected function getViewData(): array
    {
        $rows = RegistryExpert::query()
            ->with('expertType')
            ->whereHas('waves', fn ($q) => $q->where('wave', 1))
            ->whereHas('waves', fn ($q) => $q->where('wave', 2))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit(500)
            ->get();

        return ['rows' => $rows];
    }
}
