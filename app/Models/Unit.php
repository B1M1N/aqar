<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Unit extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['property_id','unit_number','type','floor','area','bedrooms','bathrooms','rent_amount','status','description','amenities'];

    protected function casts(): array
    {
        return ['area'=>'decimal:2','rent_amount'=>'decimal:2','floor'=>'integer','bedrooms'=>'integer','bathrooms'=>'integer','amenities'=>'array'];
    }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults()->logFillable()->logOnlyDirty(); }

    public function scopeAvailable(Builder $q): Builder { return $q->where('status','available'); }
    public function scopeOccupied(Builder $q): Builder { return $q->where('status','occupied'); }
    public function scopeByProperty(Builder $q, int $id): Builder { return $q->where('property_id',$id); }
    public function scopeByType(Builder $q, string $t): Builder { return $q->where('type',$t); }

    public function property(): BelongsTo { return $this->belongsTo(Property::class); }
    public function leases(): HasMany { return $this->hasMany(Lease::class); }
    public function activeLease(): HasOne { return $this->hasOne(Lease::class)->where('status','active')->latestOfMany(); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
    public function maintenanceRequests(): HasMany { return $this->hasMany(MaintenanceRequest::class); }
}