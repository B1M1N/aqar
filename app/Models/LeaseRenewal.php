<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaseRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'lease_id', 'old_end_date', 'new_end_date',
        'new_rent_amount', 'renewed_by',
    ];

    protected $casts = [
        'old_end_date'      => 'date',
        'new_end_date'      => 'date',
        'new_rent_amount'   => 'decimal:2',
    ];

    // ── Relations ────────────────────────────────────────────────────────────

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    public function renewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renewed_by');
    }
}
