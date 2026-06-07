<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovementHistory extends Model
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
     * @var array<int, string>
     */
    protected $fillable = [
        'machine_id',
        'from_department_id',
        'to_department_id',
        'from_location_id',
        'to_location_id',
        'moved_at',
        'reason',
        'created_by',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'moved_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Get the machine that this movement belongs to.
     */
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Get the source department.
     */
    public function fromDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'from_department_id');
    }

    /**
     * Get the destination department.
     */
    public function toDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    /**
     * Get the source location.
     */
    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    /**
     * Get the destination location.
     */
    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    /**
     * Get the user who created this record.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if department changed.
     */
    public function hasDepartmentChanged(): bool
    {
        return $this->from_department_id !== $this->to_department_id;
    }

    /**
     * Check if location changed.
     */
    public function hasLocationChanged(): bool
    {
        return $this->from_location_id !== $this->to_location_id;
    }

    /**
     * Get a summary of the movement.
     */
    public function getSummaryAttribute(): string
    {
        $parts = [];
        
        if ($this->hasDepartmentChanged()) {
            $from = $this->fromDepartment?->name ?? 'N/A';
            $to = $this->toDepartment?->name ?? 'N/A';
            $parts[] = "Department: {$from} → {$to}";
        }
        
        if ($this->hasLocationChanged()) {
            $from = $this->fromLocation?->name ?? 'N/A';
            $to = $this->toLocation?->name ?? 'N/A';
            $parts[] = "Location: {$from} → {$to}";
        }
        
        return implode('; ', $parts) ?: 'No change';
    }
}
