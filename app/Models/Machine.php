<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Machine extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'machine_code',
        'machine_name',
        'description',
        'category_id',
        'machine_type_id',
        'department_id',
        'location_id',
        'serial_number',
        'status',
        'purchase_date',
        'remarks',
        'created_by',
        'updated_by',
        // New fields
        'machine_type',
        'model',
        'machine_group',
        'engine_type',
        'engine_serial_number',
        'plate_number',
        'power',
        'weight',
        'purchase_order_number',
        'received_date',
        'manufacturer',
        'supplier',
        'price',
        'manufacturing_year',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'date',
        'received_date' => 'date',
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    /**
     * Available statuses for machines.
     */
    public const STATUSES = [
        'working' => 'Working',
        'faulty' => 'Faulty',
        'disposed' => 'Disposed',
        'under_maintenance' => 'Under Maintenance',
    ];

    /**
     * Get the category that owns the machine.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the machine type of the machine.
     */
    public function machineType(): BelongsTo
    {
        return $this->belongsTo(MachineType::class);
    }

    /**
     * Get the department that owns the machine.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the location of the machine.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the user who created this machine.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this machine.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the maintenance records for this machine.
     */
    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class)->orderBy('performed_at', 'desc');
    }

    /**
     * Get the movement history for this machine.
     */
    public function movementHistories(): HasMany
    {
        return $this->hasMany(MovementHistory::class)->orderBy('moved_at', 'desc');
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to filter by department.
     */
    public function scopeDepartment(Builder $query, int $departmentId): Builder
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope to filter by location.
     */
    public function scopeLocation(Builder $query, int $locationId): Builder
    {
        return $query->where('location_id', $locationId);
    }

    /**
     * Scope to search by code, name, or serial number.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('machine_code', 'like', "%{$search}%")
              ->orWhere('machine_name', 'like', "%{$search}%")
              ->orWhere('serial_number', 'like', "%{$search}%");
        });
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Check if machine is working.
     */
    public function isWorking(): bool
    {
        return $this->status === 'working';
    }

    /**
     * Check if machine is faulty.
     */
    public function isFaulty(): bool
    {
        return $this->status === 'faulty';
    }

    /**
     * Check if machine is disposed.
     */
    public function isDisposed(): bool
    {
        return $this->status === 'disposed';
    }

    /**
     * Get the latest maintenance record.
     */
    public function latestMaintenance()
    {
        return $this->maintenanceRecords()->latest('performed_at')->first();
    }

    /**
     * Get the next scheduled maintenance date.
     */
    public function getNextMaintenanceDateAttribute()
    {
        $latest = $this->maintenanceRecords()
            ->whereNotNull('next_maintenance_date')
            ->latest('performed_at')
            ->first();
        
        return $latest?->next_maintenance_date;
    }
}
