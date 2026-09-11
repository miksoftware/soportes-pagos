<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'uno@asescobpo.com'],
            [
                'name' => 'Administrador',
                'password' => 'password123',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'usuario@asescobpo.com'],
            [
                'name' => 'Ejecutivo Cobranzas',
                'password' => 'password123',
                'role' => 'user',
            ]
        );
    }
}
