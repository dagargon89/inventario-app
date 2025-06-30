<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'unit_cost',
        'reason',
        'reference_document',
        'notes',
        'lot_number',
        'expires_at',
        'inventory_item_id',
        'warehouse_id',
        'warehouse_bin_id',
        'nullable_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'quantity' => 'decimal:4',
            'quantity_before' => 'decimal:4',
            'quantity_after' => 'decimal:4',
            'unit_cost' => 'decimal:4',
            'expires_at' => 'date',
            'created_at' => 'timestamp',
            'inventory_item_id' => 'integer',
            'warehouse_id' => 'integer',
            'warehouse_bin_id' => 'integer',
            'nullable_id' => 'integer',
        ];
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function warehouseBin(): BelongsTo
    {
        return $this->belongsTo(WarehouseBin::class);
    }

    public function $this->belongsTo(User::class)able(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
