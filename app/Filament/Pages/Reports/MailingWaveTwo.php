<?php

namespace App\Filament\Pages\Reports;

use App\Models\MailingContact;
use Filament\Pages\Page;

class MailingWaveTwo extends Page
{
    protected string $view = 'filament.pages.reports.mailing-wave-two';

    protected static \UnitEnum|string|null $navigationGroup = 'Relatorios';

    protected static ?string $navigationLabel = 'Mailing Onda 2';

    protected static ?int $navigationSort = 31;

    protected function getViewData(): array
    {
        $rows = MailingContact::query()
            ->whereHas('waves', fn ($q) => $q->where('wave', 2))
            ->orderBy('interviewee_name')
            ->limit(500)
            ->get();

        return ['rows' => $rows];
    }
}
