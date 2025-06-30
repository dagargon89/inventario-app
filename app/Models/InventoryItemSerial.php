<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItemSerial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serial_number',
        'status',
        'inventory_item_id',
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
            'inventory_item_id' => 'integer',
            'nullable_id' => 'integer',
        ];
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function $this->belongsTo(WarehouseBin::class)able(): BelongsTo
    {
        return $this->belongsTo(WarehouseBin::class);
    }

    public function $this->belongsTo(InventoryRequestItem::class)able(): BelongsTo
    {
        return $this->belongsTo(InventoryRequestItem::class);
    }
}
