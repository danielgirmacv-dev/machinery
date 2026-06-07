<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class MaintenanceRecord extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'machine_id',
        'maintenance_type',
        'description',
        'performed_by',
        'performed_at',
        'next_maintenance_date',
        'cost',
        'remarks',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'performed_at' => 'date',
        'next_maintenance_date' => 'date',
        'cost' => 'decimal:2',
    ];

    /**
     * Available maintenance types.
     */
    public const TYPES = [
        'preventive' => 'Preventive',
        'corrective' => 'Corrective',
        'inspection' => 'Inspection',
    ];

    /**
     * Get the machine that this record belongs to.
     */
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Get the user who created this record.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to filter by maintenance type.
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('maintenance_type', $type);
    }

    /**
     * Scope to filter by machine.
     */
    public function scopeForMachine(Builder $query, int $machineId): Builder
    {
        return $query->where('machine_id', $machineId);
    }

    /**
     * Scope to get upcoming maintenance.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->orderBy('next_maintenance_date');
    }

    /**
     * Scope to get overdue maintenance.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<', now())
            ->orderBy('next_maintenance_date');
    }

    /**
     * Get the type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->maintenance_type] ?? $this->maintenance_type;
    }

    /**
     * Check if next maintenance is overdue.
     */
    public function isOverdue(): bool
    {
        if (!$this->next_maintenance_date) {
            return false;
        }
        
        return $this->next_maintenance_date->isPast();
    }
}
