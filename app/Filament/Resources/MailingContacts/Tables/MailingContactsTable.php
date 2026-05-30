<?php

namespace App\Filament\Resources\MailingContacts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MailingContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('interviewee_name')->label('Entrevistado')->searchable()->sortable(),
                TextColumn::make('company')->label('Empresa')->searchable(),
                TextColumn::make('occupation')->label('Cargo')->searchable(),
                TextColumn::make('city')->label('Cidade')->searchable(),
                IconColumn::make('is_active')->label('Ativo')->boolean(),
                TextColumn::make('updated_at')->label('Atualizado')->since(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Ativo'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
