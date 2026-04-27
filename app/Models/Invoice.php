<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Invoice extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['lease_id','tenant_id','unit_id','amount','due_date','paid_date','type','status','description','notes'];

    protected function casts(): array { return ['due_date'=>'date','paid_date'=>'date','amount'=>'decimal:2']; }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults()->logFillable()->logOnlyDirty(); }

    public function scopePending(Builder $q): Builder { return $q->where('status','pending'); }
    public function scopeLate(Builder $q): Builder { return $q->where('status','late'); }
    public function scopePaid(Builder $q): Builder { return $q->where('status','paid'); }
    public function scopeUnpaid(Builder $q): Builder { return $q->whereIn('status',['pending','late']); }
    public function scopeForMonth(Builder $q, int $year, int $month): Builder { return $q->whereYear('due_date',$year)->whereMonth('due_date',$month); }
    public function scopeOverdue(Builder $q): Builder { return $q->whereIn('status',['pending','late'])->whereDate('due_date','<',now()->toDateString()); }

    public function getIsLateAttribute(): bool { return in_array($this->status,['pending','late'])&&$this->due_date->isPast(); }
    public function getAmountPaidAttribute(): float { return (float)$this->payments()->sum('amount'); }
    public function getAmountDueAttribute(): float { return max(0,(float)$this->amount-$this->getAmountPaidAttribute()); }

    public function lease(): BelongsTo { return $this->belongsTo(Lease::class); }
    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function unit(): BelongsTo { return $this->belongsTo(Unit::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
}