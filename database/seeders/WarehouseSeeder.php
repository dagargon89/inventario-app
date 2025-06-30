<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\WarehouseBin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear almacén principal
        $warehouse1 = Warehouse::create([
            'name' => 'Almacén Principal',
            'location_description' => 'Edificio A, Planta Baja, Zona Industrial Norte',
            'is_active' => true,
        ]);

        // Crear ubicaciones para el almacén principal
        $this->createWarehouseBins($warehouse1, [
            'A-01-01' => 'Estante A, Nivel 1, Posición 1 - Equipos Electrónicos',
            'A-01-02' => 'Estante A, Nivel 1, Posición 2 - Equipos Electrónicos',
            'A-01-03' => 'Estante A, Nivel 1, Posición 3 - Equipos Electrónicos',
            'A-02-01' => 'Estante A, Nivel 2, Posición 1 - Herramientas',
            'A-02-02' => 'Estante A, Nivel 2, Posición 2 - Herramientas',
            'A-02-03' => 'Estante A, Nivel 2, Posición 3 - Herramientas',
            'B-01-01' => 'Estante B, Nivel 1, Posición 1 - Materiales de Construcción',
            'B-01-02' => 'Estante B, Nivel 1, Posición 2 - Materiales de Construcción',
            'B-02-01' => 'Estante B, Nivel 2, Posición 1 - Equipos Médicos',
            'B-02-02' => 'Estante B, Nivel 2, Posición 2 - Equipos Médicos',
        ]);

        // Crear almacén secundario
        $warehouse2 = Warehouse::create([
            'name' => 'Almacén Secundario',
            'location_description' => 'Edificio B, Planta Alta, Zona Comercial',
            'is_active' => true,
        ]);

        // Crear ubicaciones para el almacén secundario
        $this->createWarehouseBins($warehouse2, [
            'C-01-01' => 'Estante C, Nivel 1, Posición 1 - Papelería',
            'C-01-02' => 'Estante C, Nivel 1, Posición 2 - Papelería',
            'C-02-01' => 'Estante C, Nivel 2, Posición 1 - Suministros de Oficina',
            'C-02-02' => 'Estante C, Nivel 2, Posición 2 - Suministros de Oficina',
            'D-01-01' => 'Estante D, Nivel 1, Posición 1 - Productos de Limpieza',
            'D-01-02' => 'Estante D, Nivel 1, Posición 2 - Productos de Limpieza',
        ]);

        // Crear almacén de refrigeración
        $warehouse3 = Warehouse::create([
            'name' => 'Almacén Refrigerado',
            'location_description' => 'Edificio C, Sótano, Zona de Refrigeración',
            'is_active' => true,
        ]);

        // Crear ubicaciones para el almacén refrigerado
        $this->createWarehouseBins($warehouse3, [
            'F-01-01' => 'Cámara Frigorífica 1, Nivel 1 - Productos Sensibles',
            'F-01-02' => 'Cámara Frigorífica 1, Nivel 2 - Productos Sensibles',
            'F-02-01' => 'Cámara Frigorífica 2, Nivel 1 - Productos Químicos',
            'F-02-02' => 'Cámara Frigorífica 2, Nivel 2 - Productos Químicos',
        ]);

        // Crear almacén inactivo
        Warehouse::create([
            'name' => 'Almacén Antiguo',
            'location_description' => 'Edificio D, Planta Baja, Zona Descontinuada',
            'is_active' => false,
        ]);
    }

    private function createWarehouseBins(Warehouse $warehouse, array $bins): void
    {
        foreach ($bins as $name => $description) {
            WarehouseBin::create([
                'name' => $name,
                'description' => $description,
                'warehouse_id' => $warehouse->id,
            ]);
        }
    }
}
