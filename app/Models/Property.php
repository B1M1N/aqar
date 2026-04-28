<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Property extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'owner_id', 'name', 'type', 'description', 'address', 'city',
        'district', 'latitude', 'longitude', 'floors', 'build_year',
        'status', 'images', 'amenities',
    ];

    protected $casts = [
        'images'    => 'array',
        'amenities' => 'array',
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    // ── Relations ────────────────────────────────────────────────────────────

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getTotalUnitsAttribute(): int
    {
        return $this->units()->count();
    }

    public function getAvailableUnitsAttribute(): int
    {
        return $this->units()->where('status', 'available')->count();
    }

    public function getOccupancyRateAttribute(): float
    {
        $total = $this->getTotalUnitsAttribute();

        if ($total === 0) {
            return 0.0;
        }

        $occupied = $this->units()->where('status', 'occupied')->count();

        return round(($occupied / $total) * 100, 1);
    }
}
