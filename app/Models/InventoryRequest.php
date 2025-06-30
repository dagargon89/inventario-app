<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryRequest extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_name',
        'event_date_start',
        'event_date_end',
        'status',
        'notes_requester',
        'notes_approver',
        'approved_at',
        'dispatched_at',
        'completed_at',
        'user_id',
        'approved_by:nullable_id',
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
            'event_date_start' => 'date',
            'event_date_end' => 'date',
            'approved_at' => 'timestamp',
            'dispatched_at' => 'timestamp',
            'completed_at' => 'timestamp',
            'user_id' => 'integer',
            'approved_by:nullable_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inventoryRequestItems(): HasMany
    {
        return $this->hasMany(InventoryRequestItem::class);
    }
}
