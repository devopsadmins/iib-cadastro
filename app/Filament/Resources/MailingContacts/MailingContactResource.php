<?php

namespace App\Filament\Resources\MailingContacts;

use App\Filament\Resources\MailingContacts\Pages\CreateMailingContact;
use App\Filament\Resources\MailingContacts\Pages\EditMailingContact;
use App\Filament\Resources\MailingContacts\Pages\ListMailingContacts;
use App\Filament\Resources\MailingContacts\Schemas\MailingContactForm;
use App\Filament\Resources\MailingContacts\Tables\MailingContactsTable;
use App\Models\MailingContact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MailingContactResource extends Resource
{
    protected static ?string $model = MailingContact::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationLabel(): string
    {
        return 'Mailing';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Cadastro';
    }

    public static function getNavigationSort(): ?int
    {
        return 30;
    }

    public static function form(Schema $schema): Schema
    {
        return MailingContactForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MailingContactsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMailingContacts::route('/'),
            'create' => CreateMailingContact::route('/create'),
            'edit' => EditMailingContact::route('/{record}/edit'),
        ];
    }
}
