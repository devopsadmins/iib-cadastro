<?php

namespace App\Filament\Resources\RegistryExperts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RegistryExpertsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('Nome')->searchable()->sortable(),
                TextColumn::make('last_name')->label('Sobrenome')->searchable()->sortable(),
                TextColumn::make('expertType.name')->label('Tipo')->searchable(),
                TextColumn::make('company')->label('Empresa')->searchable(),
                TextColumn::make('city')->label('Cidade')->searchable(),
                TextColumn::make('email')->label('E-mail')->searchable(),
                IconColumn::make('is_active')->label('Ativo')->boolean(),
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
