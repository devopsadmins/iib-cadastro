<?php

namespace App\Filament\Resources\MailingContacts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MailingContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('interviewee_name')
                    ->label('Entrevistado')
                    ->required()
                    ->maxLength(255),
                TextInput::make('company')->label('Empresa')->maxLength(255),
                TextInput::make('occupation')->label('Cargo')->maxLength(255),
                TextInput::make('city')->label('Cidade')->maxLength(120),
                TextInput::make('linkedin_url')->label('LinkedIn')->url()->maxLength(500),
                TextInput::make('company_website')->label('Site da empresa')->url()->maxLength(500),
                TextInput::make('merco_approval_status')->label('Status aprovacao Merco')->maxLength(120),
                Select::make('waves')
                    ->label('Ondas vinculadas')
                    ->relationship('waves', 'label')
                    ->multiple()
                    ->preload(),
                Toggle::make('is_active')->label('Ativo')->default(true),
            ]);
    }
}
