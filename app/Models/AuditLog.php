<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;

class AuditLog extends Model
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
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Available actions.
     */
    public const ACTIONS = [
        'create' => 'Created',
        'update' => 'Updated',
        'delete' => 'Deleted',
    ];

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the auditable model.
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter by action.
     */
    public function scopeAction(Builder $query, string $action): Builder
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by model type.
     */
    public function scopeForModel(Builder $query, string $modelType): Builder
    {
        return $query->where('auditable_type', $modelType);
    }

    /**
     * Get the action label.
     */
    public function getActionLabelAttribute(): string
    {
        return self::ACTIONS[$this->action] ?? $this->action;
    }

    /**
     * Get the model name without namespace.
     */
    public function getModelNameAttribute(): string
    {
        return class_basename($this->auditable_type);
    }

    /**
     * Get the changes made.
     */
    public function getChangesAttribute(): array
    {
        if ($this->action === 'create') {
            return $this->new_values ?? [];
        }
        
        if ($this->action === 'delete') {
            return $this->old_values ?? [];
        }
        
        // For updates, show what changed
        $changes = [];
        $oldValues = $this->old_values ?? [];
        $newValues = $this->new_values ?? [];
        
        foreach ($newValues as $key => $newValue) {
            $oldValue = $oldValues[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }
        
        return $changes;
    }
}
