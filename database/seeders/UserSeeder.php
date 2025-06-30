<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@inventario.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Crear usuarios de prueba
        User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@inventario.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'María García',
            'email' => 'maria.garcia@inventario.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Carlos López',
            'email' => 'carlos.lopez@inventario.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Crear usuarios adicionales usando factory
        User::factory(5)->create();
    }
}
