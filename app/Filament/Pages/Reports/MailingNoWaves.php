<?php

namespace App\Filament\Pages\Reports;

use App\Models\MailingContact;
use Filament\Pages\Page;

class MailingNoWaves extends Page
{
    protected string $view = 'filament.pages.reports.mailing-no-waves';

    public static function getNavigationGroup(): ?string
    {
        return 'Relatorios';
    }

    public static function getNavigationLabel(): string
    {
        return 'Mailing Sem Vinculo';
    }

    public static function getNavigationSort(): ?int
    {
        return 33;
    }

    protected function getViewData(): array
    {
        $rows = MailingContact::query()
            ->doesntHave('waves')
            ->orderBy('interviewee_name')
            ->limit(500)
            ->get();

        return ['rows' => $rows];
    }
}
