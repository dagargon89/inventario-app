<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::all();

        // Equipos Electrónicos
        $this->createInventoryItems([
            [
                'sku' => 'LAP-001',
                'name' => 'Laptop Dell Inspiron 15',
                'description' => 'Laptop de 15 pulgadas, Intel i5, 8GB RAM, 256GB SSD',
                'unit_of_measure' => 'Unidad',
                'status' => 'active',
                'tracking_type' => 'serial',
                'attributes' => ['marca' => 'Dell', 'modelo' => 'Inspiron 15', 'procesador' => 'Intel i5'],
            ],
            [
                'sku' => 'MON-001',
                'name' => 'Monitor Samsung 24"',
                'description' => 'Monitor LED de 24 pulgadas, Full HD, HDMI',
                'unit_of_measure' => 'Unidad',
                'status' => 'active',
                'tracking_type' => 'serial',
                'attributes' => ['marca' => 'Samsung', 'tamaño' => '24"', 'resolución' => '1920x1080'],
            ],
            [
                'sku' => 'TEC-001',
                'name' => 'Teclado Mecánico RGB',
                'description' => 'Teclado mecánico con switches Cherry MX, retroiluminación RGB',
                'unit_of_measure' => 'Unidad',
                'status' => 'active',
                'tracking_type' => 'quantity',
                'attributes' => ['marca' => 'Logitech', 'tipo' => 'Mecánico', 'switches' => 'Cherry MX'],
            ],
        ], $suppliers->where('name', 'Tecnología Avanzada S.A.')->first());

        // Herramientas Industriales
        $this->createInventoryItems([
            [
                'sku' => 'HERR-001',
                'name' => 'Taladro Eléctrico Profesional',
                'description' => 'Taladro de 1/2 pulgada, 1200W, con maletín',
                'unit_of_measure' => 'Unidad',
                'status' => 'active',
                'tracking_type' => 'serial',
                'attributes' => ['marca' => 'DeWalt', 'potencia' => '1200W', 'tipo' => 'Eléctrico'],
            ],
            [
                'sku' => 'HERR-002',
                'name' => 'Juego de Destornilladores',
                'description' => 'Set de 6 destornilladores con mango ergonómico',
                'unit_of_measure' => 'Set',
                'status' => 'active',
                'tracking_type' => 'quantity',
                'attributes' => ['marca' => 'Stanley', 'piezas' => 6, 'tipo' => 'Manual'],
            ],
            [
                'sku' => 'HERR-003',
                'name' => 'Sierra Circular',
                'description' => 'Sierra circular de 7-1/4 pulgadas, 1800W',
                'unit_of_measure' => 'Unidad',
                'status' => 'active',
                'tracking_type' => 'serial',
                'attributes' => ['marca' => 'Makita', 'diámetro' => '7-1/4"', 'potencia' => '1800W'],
            ],
        ], $suppliers->where('name', 'Suministros Industriales del Norte')->first());

        // Materiales de Construcción
        $this->createInventoryItems([
            [
                'sku' => 'CONS-001',
                'name' => 'Cemento Portland',
                'description' => 'Cemento Portland tipo I, bolsa de 50kg',
                'unit_of_measure' => 'Bolsa',
                'status' => 'active',
                'tracking_type' => 'quantity',
                'attributes' => ['marca' => 'Cemex', 'peso' => '50kg', 'tipo' => 'Portland I'],
            ],
            [
                'sku' => 'CONS-002',
                'name' => 'Varilla de Acero 3/8"',
                'description' => 'Varilla corrugada de 3/8 pulgadas, 6 metros',
                'unit_of_measure' => 'Pieza',
                'status' => 'active',
                'tracking_type' => 'quantity',
                'attributes' => ['marca' => 'Deacero', 'diámetro' => '3/8"', 'longitud' => '6m'],
            ],
            [
                'sku' => 'CONS-003',
                'name' => 'Ladrillo Rojo',
                'description' => 'Ladrillo rojo estándar, 7x14x28 cm',
                'unit_of_measure' => 'Pieza',
                'status' => 'active',
                'tracking_type' => 'quantity',
                'attributes' => ['marca' => 'Ladrillera del Norte', 'dimensiones' => '7x14x28cm'],
            ],
        ], $suppliers->where('name', 'Materiales de Construcción Express')->first());

        // Equipos Médicos
        $this->createInventoryItems([
            [
                'sku' => 'MED-001',
                'name' => 'Estetoscopio Littmann',
                'description' => 'Estetoscopio cardiólogo, doble campana',
                'unit_of_measure' => 'Unidad',
                'status' => 'active',
                'tracking_type' => 'serial',
                'attributes' => ['marca' => '3M Littmann', 'tipo' => 'Cardiólogo', 'campanas' => 2],
            ],
            [
                'sku' => 'MED-002',
                'name' => 'Tensiómetro Digital',
                'description' => 'Tensiómetro automático de brazo, con memoria',
                'unit_of_measure' => 'Unidad',
                'status' => 'active',
                'tracking_type' => 'serial',
                'attributes' => ['marca' => 'Omron', 'tipo' => 'Digital', 'ubicación' => 'Brazo'],
            ],
            [
                'sku' => 'MED-003',
                'name' => 'Termómetro Infrarrojo',
                'description' => 'Termómetro sin contacto, medición en 1 segundo',
                'unit_of_measure' => 'Unidad',
                'status' => 'active',
                'tracking_type' => 'serial',
                'attributes' => ['marca' => 'Braun', 'tipo' => 'Infrarrojo', 'tiempo' => '1s'],
            ],
        ], $suppliers->where('name', 'Equipos Médicos Profesionales')->first());

        // Papelería y Oficina
        $this->createInventoryItems([
            [
                'sku' => 'PAP-001',
                'name' => 'Papel Bond A4',
                'description' => 'Papel bond blanco, 75 gramos, 500 hojas',
                'unit_of_measure' => 'Resma',
                'status' => 'active',
                'tracking_type' => 'quantity',
                'attributes' => ['marca' => 'Chamex', 'gramaje' => '75g', 'hojas' => 500],
            ],
            [
                'sku' => 'PAP-002',
                'name' => 'Lápices HB',
                'description' => 'Lápices de grafito HB, caja de 12 unidades',
                'unit_of_measure' => 'Caja',
                'status' => 'active',
                'tracking_type' => 'quantity',
                'attributes' => ['marca' => 'Faber-Castell', 'dureza' => 'HB', 'unidades' => 12],
            ],
            [
                'sku' => 'PAP-003',
                'name' => 'Impresora Láser HP',
                'description' => 'Impresora láser monocromática, 20 ppm',
                'unit_of_measure' => 'Unidad',
                'status' => 'active',
                'tracking_type' => 'serial',
                'attributes' => ['marca' => 'HP', 'tipo' => 'Láser', 'velocidad' => '20 ppm'],
            ],
        ], $suppliers->where('name', 'Papelería y Oficina Central')->first());

        // Crear artículos adicionales usando factory
        InventoryItem::factory(10)->create()->each(function ($item) use ($suppliers) {
            $item->suppliers()->attach($suppliers->random());
        });
    }

    private function createInventoryItems(array $items, Supplier $supplier): void
    {
        foreach ($items as $itemData) {
            $item = InventoryItem::create($itemData);
            $item->suppliers()->attach($supplier);
        }
    }
}
