<?php

namespace App\Filament\Pages\Reports;

use App\Models\RegistryExpert;
use Filament\Pages\Page;

class ExpertsNoWaves extends Page
{
    protected string $view = 'filament.pages.reports.experts-no-waves';

    protected static \UnitEnum|string|null $navigationGroup = 'Relatorios';

    protected static ?string $navigationLabel = 'Especialistas Sem Vinculo';

    protected static ?int $navigationSort = 23;

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
