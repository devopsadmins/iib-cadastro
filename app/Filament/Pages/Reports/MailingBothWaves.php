<?php

namespace App\Filament\Pages\Reports;

use App\Models\MailingContact;
use Filament\Pages\Page;

class MailingBothWaves extends Page
{
    protected string $view = 'filament.pages.reports.mailing-both-waves';

    public static function getNavigationGroup(): ?string
    {
        return 'Relatorios';
    }

    public static function getNavigationLabel(): string
    {
        return 'Mailing Ambas Ondas';
    }

    public static function getNavigationSort(): ?int
    {
        return 32;
    }

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
