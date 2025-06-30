<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryRequestItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity_requested',
        'quantity_dispatched',
        'quantity_returned',
        'quantity_missing',
        'quantity_damaged',
        'notes',
        'inventory_request_id',
        'inventory_item_id',
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
            'quantity_requested' => 'decimal:4',
            'quantity_dispatched' => 'decimal:4',
            'quantity_returned' => 'decimal:4',
            'quantity_missing' => 'decimal:4',
            'quantity_damaged' => 'decimal:4',
            'inventory_request_id' => 'integer',
            'inventory_item_id' => 'integer',
        ];
    }

    public function inventoryRequest(): BelongsTo
    {
        return $this->belongsTo(InventoryRequest::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function inventoryItemSerials(): HasMany
    {
        return $this->hasMany(InventoryItemSerial::class);
    }
}
