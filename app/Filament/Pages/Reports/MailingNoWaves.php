<?php

namespace App\Filament\Pages\Reports;

use App\Models\MailingContact;
use Filament\Pages\Page;

class MailingNoWaves extends Page
{
    protected string $view = 'filament.pages.reports.mailing-no-waves';

    protected static \UnitEnum|string|null $navigationGroup = 'Relatorios';

    protected static ?string $navigationLabel = 'Mailing Sem Vinculo';

    protected static ?int $navigationSort = 33;

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
