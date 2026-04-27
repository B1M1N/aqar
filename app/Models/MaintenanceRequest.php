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

class MaintenanceRequest extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['unit_id','tenant_id','assigned_to','title','description','type','priority','status','cost','scheduled_at','completed_at','images'];

    protected function casts(): array { return ['images'=>'array','cost'=>'decimal:2','scheduled_at'=>'datetime','completed_at'=>'datetime']; }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults()->logFillable()->logOnlyDirty(); }

    public function scopeOpen(Builder $q): Builder { return $q->whereIn('status',['pending','assigned','in_progress']); }
    public function scopeUrgent(Builder $q): Builder { return $q->where('priority','urgent'); }
    public function scopeUnassigned(Builder $q): Builder { return $q->whereNull('assigned_to'); }

    public function getPriorityColorAttribute(): string { return match($this->priority){'low'=>'green','medium'=>'yellow','high'=>'orange','urgent'=>'red',default=>'gray'}; }
    public function getStatusLabelAttribute(): string { return match($this->status){'pending'=>'قيد الانتظار','assigned'=>'مُسند','in_progress'=>'جاري التنفيذ','completed'=>'مكتمل','cancelled'=>'ملغي',default=>$this->status}; }

    public function unit(): BelongsTo { return $this->belongsTo(Unit::class); }
    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function assignedTo(): BelongsTo { return $this->belongsTo(User::class,'assigned_to'); }
    public function updates(): HasMany { return $this->hasMany(MaintenanceUpdate::class); }
}