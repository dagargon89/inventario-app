<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\InventoryRequest;
use App\Models\InventoryRequestItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventoryRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $items = InventoryItem::all();

        // Crear solicitudes de diferentes estados
        $this->createPendingRequests($users, $items);
        $this->createApprovedRequests($users, $items);
        $this->createCompletedRequests($users, $items);
    }

    private function createPendingRequests($users, $items): void
    {
        for ($i = 0; $i < 5; $i++) {
            $request = InventoryRequest::create([
                'event_name' => $this->getEventName(),
                'event_date_start' => now()->addDays(rand(7, 30)),
                'event_date_end' => now()->addDays(rand(31, 60)),
                'status' => 'pending',
                'notes_requester' => 'Solicitud pendiente de aprobación',
                'notes_approver' => null,
                'approved_at' => null,
                'dispatched_at' => null,
                'completed_at' => null,
                'user_id' => $users->random()->id,
                'approved_by_id' => null,
            ]);

            $this->createRequestItems($request, $items);
        }
    }

    private function createApprovedRequests($users, $items): void
    {
        for ($i = 0; $i < 3; $i++) {
            $request = InventoryRequest::create([
                'event_name' => $this->getEventName(),
                'event_date_start' => now()->addDays(rand(1, 7)),
                'event_date_end' => now()->addDays(rand(8, 15)),
                'status' => 'approved',
                'notes_requester' => 'Solicitud aprobada, pendiente de despacho',
                'notes_approver' => 'Solicitud aprobada por el departamento de compras',
                'approved_at' => now()->subDays(rand(1, 5)),
                'dispatched_at' => null,
                'completed_at' => null,
                'user_id' => $users->random()->id,
                'approved_by_id' => $users->random()->id,
            ]);

            $this->createRequestItems($request, $items);
        }
    }

    private function createCompletedRequests($users, $items): void
    {
        for ($i = 0; $i < 2; $i++) {
            $request = InventoryRequest::create([
                'event_name' => $this->getEventName(),
                'event_date_start' => now()->subDays(rand(10, 30)),
                'event_date_end' => now()->subDays(rand(1, 9)),
                'status' => 'completed',
                'notes_requester' => 'Solicitud completada exitosamente',
                'notes_approver' => 'Solicitud procesada y entregada',
                'approved_at' => now()->subDays(rand(15, 25)),
                'dispatched_at' => now()->subDays(rand(10, 20)),
                'completed_at' => now()->subDays(rand(1, 10)),
                'user_id' => $users->random()->id,
                'approved_by_id' => $users->random()->id,
            ]);

            $this->createRequestItems($request, $items);
        }
    }

    private function createRequestItems($request, $items): void
    {
        $selectedItems = $items->random(rand(2, 5));

        foreach ($selectedItems as $item) {
            $quantityRequested = rand(1, 10);
            $quantityDispatched = $request->status === 'completed' ? $quantityRequested : rand(0, $quantityRequested);
            $quantityReturned = $request->status === 'completed' ? rand(0, $quantityDispatched * 0.2) : 0;
            $quantityMissing = $request->status === 'completed' ? rand(0, $quantityDispatched * 0.1) : 0;
            $quantityDamaged = $request->status === 'completed' ? rand(0, $quantityDispatched * 0.05) : 0;

            InventoryRequestItem::create([
                'quantity_requested' => $quantityRequested,
                'quantity_dispatched' => $quantityDispatched,
                'quantity_returned' => $quantityReturned,
                'quantity_missing' => $quantityMissing,
                'quantity_damaged' => $quantityDamaged,
                'notes' => 'Item solicitado para evento',
                'inventory_request_id' => $request->id,
                'inventory_item_id' => $item->id,
            ]);
        }
    }

    private function getEventName(): string
    {
        $events = [
            'Conferencia Anual de Tecnología',
            'Exposición de Productos Industriales',
            'Seminario de Capacitación',
            'Feria Comercial Regional',
            'Evento Corporativo Q4',
            'Workshop de Innovación',
            'Presentación de Nuevos Productos',
            'Reunión de Distribuidores',
            'Convención de Proveedores',
            'Exhibición de Equipos Médicos',
        ];

        return $events[array_rand($events)];
    }
}
