<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryStock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity',
        'quantity_reserved',
        'low_stock_threshold',
        'last_movement_at',
        'inventory_item_id',
        'warehouse_bin_id',
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
            'quantity_reserved' => 'decimal:4',
            'low_stock_threshold' => 'decimal:4',
            'last_movement_at' => 'timestamp',
            'inventory_item_id' => 'integer',
            'warehouse_bin_id' => 'integer',
        ];
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function warehouseBin(): BelongsTo
    {
        return $this->belongsTo(WarehouseBin::class);
    }
}
