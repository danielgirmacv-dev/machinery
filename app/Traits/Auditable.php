<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    /**
     * Boot the auditable trait.
     */
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            self::audit('create', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            
            // Remove timestamps from changes
            unset($changes['created_at'], $changes['updated_at']);
            
            if (!empty($changes)) {
                self::audit('update', $model, $model->getOriginal(), $changes);
            }
        });

        static::deleted(function ($model) {
            self::audit('delete', $model, $model->getAttributes(), null);
        });
    }

    /**
     * Create an audit log entry.
     */
    protected static function audit(string $action, $model, ?array $oldValues, ?array $newValues): void
    {
        // Filter out sensitive fields
        $sensitiveFields = ['password', 'remember_token'];
        
        if ($oldValues) {
            $oldValues = array_diff_key($oldValues, array_flip($sensitiveFields));
        }
        
        if ($newValues) {
            $newValues = array_diff_key($newValues, array_flip($sensitiveFields));
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }
}
