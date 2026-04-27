<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Property extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['owner_id','name','type','description','address','city','district','latitude','longitude','floors','build_year','status','images','amenities'];

    protected function casts(): array
    {
        return ['images'=>'array','amenities'=>'array','latitude'=>'decimal:7','longitude'=>'decimal:7','floors'=>'integer','build_year'=>'integer'];
    }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults()->logFillable()->logOnlyDirty(); }

    public function scopeActive(Builder $q): Builder { return $q->where('status','active'); }
    public function scopeByType(Builder $q, string $t): Builder { return $q->where('type',$t); }
    public function scopeByCity(Builder $q, string $c): Builder { return $q->where('city',$c); }

    public function getTotalUnitsAttribute(): int { return $this->units()->count(); }
    public function getAvailableUnitsAttribute(): int { return $this->units()->where('status','available')->count(); }
    public function getOccupiedUnitsAttribute(): int { return $this->units()->where('status','occupied')->count(); }
    public function getOccupancyRateAttribute(): float
    {
        $total = $this->getTotalUnitsAttribute();
        return $total === 0 ? 0.0 : round(($this->getOccupiedUnitsAttribute()/$total)*100,1);
    }
    public function getMonthlyRevenueAttribute(): float
    {
        return (float)$this->units()->where('status','occupied')
            ->join('leases',fn($j)=>$j->on('leases.unit_id','=','units.id')->where('leases.status','active')->whereNull('leases.deleted_at'))
            ->sum('leases.rent_amount');
    }

    public function owner(): BelongsTo { return $this->belongsTo(User::class,'owner_id'); }
    public function units(): HasMany { return $this->hasMany(Unit::class); }
}