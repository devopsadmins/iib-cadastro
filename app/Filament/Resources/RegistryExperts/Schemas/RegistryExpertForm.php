<?php

namespace App\Filament\Resources\RegistryExperts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RegistryExpertForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('expert_type_id')
                    ->label('Tipo')
                    ->relationship('expertType', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('first_name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(120),
                TextInput::make('last_name')
                    ->label('Sobrenome')
                    ->required()
                    ->maxLength(120),
                TextInput::make('company')->label('Empresa')->maxLength(255),
                TextInput::make('occupation')->label('Cargo')->maxLength(255),
                Textarea::make('address')->label('Endereco')->columnSpanFull(),
                TextInput::make('city')->label('Cidade')->maxLength(120),
                TextInput::make('postal_code')->label('CEP')->maxLength(20),
                TextInput::make('phone')->label('Telefone')->maxLength(40),
                TextInput::make('email')->label('E-mail')->email()->maxLength(255),
                Select::make('registration_wave_id')
                    ->label('Onda de cadastro')
                    ->relationship('registrationWave', 'label')
                    ->searchable()
                    ->preload(),
                TextInput::make('registration_wave_note')->label('Observacao da onda')->maxLength(80),
                Select::make('waves')
                    ->label('Ondas vinculadas')
                    ->relationship('waves', 'label')
                    ->multiple()
                    ->preload(),
                Toggle::make('is_active')->label('Ativo')->default(true),
            ]);
    }
}
