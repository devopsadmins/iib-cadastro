<?php

namespace App\Filament\Pages\Reports;

use App\Models\MailingContact;
use Filament\Pages\Page;

class MailingWaveOne extends Page
{
    protected string $view = 'filament.pages.reports.mailing-wave-one';

    public static function getNavigationGroup(): ?string
    {
        return 'Relatorios';
    }

    public static function getNavigationLabel(): string
    {
        return 'Mailing Onda 1';
    }

    public static function getNavigationSort(): ?int
    {
        return 30;
    }

    protected function getViewData(): array
    {
        $rows = MailingContact::query()
            ->whereHas('waves', fn ($q) => $q->where('wave', 1))
            ->orderBy('interviewee_name')
            ->limit(500)
            ->get();

        return ['rows' => $rows];
    }
}
