<?php

namespace Database\Seeders;

use App\Models\ExpertType;
use Illuminate\Database\Seeder;

class ExpertTypesSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['slug' => 'analista-financeiro', 'name' => 'Analista Financeiro', 'sort_order' => 1],
            ['slug' => 'jornalista-info-economica', 'name' => 'Jornalista Info Economica', 'sort_order' => 2],
            ['slug' => 'jornalista-social', 'name' => 'Jornalista Social', 'sort_order' => 3],
            ['slug' => 'governo', 'name' => 'Governo', 'sort_order' => 4],
            ['slug' => 'ong', 'name' => 'ONG', 'sort_order' => 5],
            ['slug' => 'sindicato', 'name' => 'Sindicato', 'sort_order' => 6],
            ['slug' => 'associacao-consumidores', 'name' => 'Associacao de Consumidores', 'sort_order' => 7],
            ['slug' => 'smm', 'name' => 'SMM', 'sort_order' => 8],
            ['slug' => 'catedraticos', 'name' => 'Catedraticos', 'sort_order' => 9],
        ];

        foreach ($items as $item) {
            ExpertType::query()->updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
