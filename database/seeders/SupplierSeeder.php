<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear proveedores de prueba
        Supplier::create([
            'name' => 'Tecnología Avanzada S.A.',
            'contact_person' => 'Ing. Roberto Martínez',
            'email' => 'ventas@tecnologiaavanzada.com',
            'phone' => '+52 55 1234 5678',
            'address' => 'Av. Insurgentes Sur 1234, Col. Del Valle, CDMX',
            'notes' => 'Proveedor principal de equipos electrónicos y computadoras',
        ]);

        Supplier::create([
            'name' => 'Suministros Industriales del Norte',
            'contact_person' => 'Lic. Ana Rodríguez',
            'email' => 'compras@suministrosnorte.com',
            'phone' => '+52 81 9876 5432',
            'address' => 'Blvd. Constitución 567, Col. Centro, Monterrey, NL',
            'notes' => 'Especialistas en herramientas y equipos industriales',
        ]);

        Supplier::create([
            'name' => 'Materiales de Construcción Express',
            'contact_person' => 'Sr. Miguel Ángel Torres',
            'email' => 'pedidos@materialesexpress.com',
            'phone' => '+52 33 4567 8901',
            'address' => 'Calzada Independencia 890, Col. Americana, Guadalajara, Jal',
            'notes' => 'Materiales de construcción y ferretería',
        ]);

        Supplier::create([
            'name' => 'Equipos Médicos Profesionales',
            'contact_person' => 'Dra. Patricia Sánchez',
            'email' => 'ventas@equiposmedicos.com',
            'phone' => '+52 55 2345 6789',
            'address' => 'Paseo de la Reforma 456, Col. Juárez, CDMX',
            'notes' => 'Equipos médicos y suministros hospitalarios',
        ]);

        Supplier::create([
            'name' => 'Papelería y Oficina Central',
            'contact_person' => 'Sra. Carmen López',
            'email' => 'pedidos@papeleriacentral.com',
            'phone' => '+52 55 3456 7890',
            'address' => 'Av. Juárez 234, Col. Centro Histórico, CDMX',
            'notes' => 'Artículos de papelería y suministros de oficina',
        ]);

        // Crear proveedores adicionales usando factory
        Supplier::factory(3)->create();
    }
}
