<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MachineType extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'category_id',
        'category_code',
        'description',
        'eec_number',
    ];

    /**
     * The category this machine type belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the machines of this type.
     */
    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class);
    }

    /**
     * Get the count of machines with this type.
     */
    public function getMachineCountAttribute(): int
    {
        return $this->machines()->count();
    }
}
