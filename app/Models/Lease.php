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

class Lease extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['unit_id','tenant_id','start_date','end_date','rent_amount','deposit_amount','payment_day','status','contract_pdf','notes'];

    protected function casts(): array
    {
        return ['start_date'=>'date','end_date'=>'date','rent_amount'=>'decimal:2','deposit_amount'=>'decimal:2','payment_day'=>'integer'];
    }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults()->logFillable()->logOnlyDirty(); }

    public function scopeActive(Builder $q): Builder { return $q->where('status','active'); }
    public function scopeExpiring(Builder $q, int $days): Builder { return $q->where('status','active')->whereDate('end_date',now()->addDays($days)->toDateString()); }
    public function scopeExpiringWithin(Builder $q, int $days): Builder { return $q->where('status','active')->whereDate('end_date','<=',now()->addDays($days)->toDateString())->whereDate('end_date','>=',now()->toDateString()); }

    public function getDaysRemainingAttribute(): int { return $this->end_date->isPast() ? 0 : (int)now()->diffInDays($this->end_date,false); }

    public function unit(): BelongsTo { return $this->belongsTo(Unit::class); }
    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function renewals(): HasMany { return $this->hasMany(LeaseRenewal::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
}