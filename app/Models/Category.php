<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the machine types in this category.
     */
    public function machineTypes(): HasMany
    {
        return $this->hasMany(MachineType::class)->orderBy('category_code');
    }

    /**
     * Get the machines in this category.
     */
    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class);
    }

    /**
     * Get the count of machines in this category.
     */
    public function getMachineCountAttribute(): int
    {
        return $this->machines()->count();
    }
}
