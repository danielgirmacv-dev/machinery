<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is IT staff.
     */
    public function isIt(): bool
    {
        return $this->role === 'it';
    }

    /**
     * Check if user is viewer only.
     */
    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    /**
     * Check if user can edit machines.
     */
    public function canEdit(): bool
    {
        return in_array($this->role, ['admin', 'it']);
    }

    /**
     * Get the machines created by this user.
     */
    public function createdMachines(): HasMany
    {
        return $this->hasMany(Machine::class, 'created_by');
    }

    /**
     * Get the audit logs for this user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
