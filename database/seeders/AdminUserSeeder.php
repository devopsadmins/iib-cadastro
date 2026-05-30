<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@institutointeligencia.com.br');
        $name = (string) env('ADMIN_NAME', 'Administrador IIB');
        $password = (string) env('ADMIN_PASSWORD', 'changeme');

        User::query()->updateOrCreate(
            ['email' => strtolower(trim($email))],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
