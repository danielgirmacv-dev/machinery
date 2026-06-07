<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'status',
        'building',
        'floor',
    ];

    /**
     * Get the machines at this location.
     */
    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class);
    }

    /**
     * Get movement histories where this was the source location.
     */
    public function movementsFrom(): HasMany
    {
        return $this->hasMany(MovementHistory::class, 'from_location_id');
    }

    /**
     * Get movement histories where this was the destination location.
     */
    public function movementsTo(): HasMany
    {
        return $this->hasMany(MovementHistory::class, 'to_location_id');
    }

    /**
     * Get the full location name including building and floor.
     */
    public function getFullNameAttribute(): string
    {
        $parts = [$this->name];
        
        if ($this->building) {
            $parts[] = $this->building;
        }
        
        if ($this->floor) {
            $parts[] = $this->floor;
        }
        
        return implode(' - ', $parts);
    }

    /**
     * Get the count of machines at this location.
     */
    public function getMachineCountAttribute(): int
    {
        return $this->machines()->count();
    }
}
