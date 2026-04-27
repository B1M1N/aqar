<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class LeaseRenewal extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['lease_id','old_end_date','new_end_date','new_rent_amount','renewed_by','notes'];

    protected function casts(): array { return ['old_end_date'=>'date','new_end_date'=>'date','new_rent_amount'=>'decimal:2']; }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults()->logFillable(); }

    public function lease(): BelongsTo { return $this->belongsTo(Lease::class); }
    public function renewedBy(): BelongsTo { return $this->belongsTo(User::class,'renewed_by'); }
}