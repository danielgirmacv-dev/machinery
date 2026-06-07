<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Get the machines in this department.
     */
    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class);
    }

    /**
     * Get movement histories where this was the source department.
     */
    public function movementsFrom(): HasMany
    {
        return $this->hasMany(MovementHistory::class, 'from_department_id');
    }

    /**
     * Get movement histories where this was the destination department.
     */
    public function movementsTo(): HasMany
    {
        return $this->hasMany(MovementHistory::class, 'to_department_id');
    }

    /**
     * Get the count of machines in this department.
     */
    public function getMachineCountAttribute(): int
    {
        return $this->machines()->count();
    }
}
