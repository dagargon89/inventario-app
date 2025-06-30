<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sku',
        'name',
        'description',
        'unit_of_measure',
        'status',
        'tracking_type',
        'attributes',
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
            'attributes' => 'array',
        ];
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class);
    }

    public function inventoryItemSerials(): HasMany
    {
        return $this->hasMany(InventoryItemSerial::class);
    }

    public function inventoryStocks(): HasMany
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function inventoryRequestItems(): HasMany
    {
        return $this->hasMany(InventoryRequestItem::class);
    }
}
