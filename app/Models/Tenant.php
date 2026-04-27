<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Tenant extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['user_id','name','email','phone','national_id','nationality','id_type','id_expiry','emergency_contact','notes'];

    protected function casts(): array { return ['emergency_contact'=>'array','id_expiry'=>'date']; }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults()->logFillable()->logOnlyDirty(); }

    public function scopeWithActiveLease(Builder $q): Builder { return $q->whereHas('leases',fn(Builder $q)=>$q->where('status','active')); }
    public function scopeSearch(Builder $q, string $term): Builder
    {
        return $q->where(fn(Builder $q)=>$q->where('name','like',"%{$term}%")->orWhere('email','like',"%{$term}%")->orWhere('phone','like',"%{$term}%")->orWhere('national_id','like',"%{$term}%"));
    }

    public function getIsIdExpiredAttribute(): bool { return $this->id_expiry && $this->id_expiry->isPast(); }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function leases(): HasMany { return $this->hasMany(Lease::class); }
    public function activeLease(): HasOne { return $this->hasOne(Lease::class)->where('status','active')->latestOfMany(); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
    public function maintenanceRequests(): HasMany { return $this->hasMany(MaintenanceRequest::class); }
}