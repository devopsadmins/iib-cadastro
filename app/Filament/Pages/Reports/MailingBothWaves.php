<?php

namespace App\Filament\Pages\Reports;

use App\Models\MailingContact;
use Filament\Pages\Page;

class MailingBothWaves extends Page
{
    protected string $view = 'filament.pages.reports.mailing-both-waves';

    protected static \UnitEnum|string|null $navigationGroup = 'Relatorios';

    protected static ?string $navigationLabel = 'Mailing Ambas Ondas';

    protected static ?int $navigationSort = 32;

    protected function getViewData(): array
    {
        $rows = MailingContact::query()
            ->whereHas('waves', fn ($q) => $q->where('wave', 1))
            ->whereHas('waves', fn ($q) => $q->where('wave', 2))
            ->orderBy('interviewee_name')
            ->limit(500)
            ->get();

        return ['rows' => $rows];
    }
}
