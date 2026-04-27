<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, LogsActivity, Notifiable;

    protected $fillable = ['name','email','password','phone','avatar','preferred_language'];
    protected $hidden   = ['password','remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function properties(): HasMany { return $this->hasMany(Property::class, 'owner_id'); }
    public function payments(): HasMany { return $this->hasMany(Payment::class, 'paid_by'); }
    public function notifications(): HasMany { return $this->hasMany(Notification::class); }
    public function maintenanceAssignments(): HasMany { return $this->hasMany(MaintenanceRequest::class, 'assigned_to'); }
    public function leaseRenewals(): HasMany { return $this->hasMany(LeaseRenewal::class, 'renewed_by'); }
    public function tenantProfile(): \Illuminate\Database\Eloquent\Relations\HasOne { return $this->hasOne(Tenant::class); }
}