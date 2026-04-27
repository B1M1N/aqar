<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceUpdate extends Model
{
    use HasFactory;

    protected $fillable = ['maintenance_request_id','user_id','note','notes','status_changed_to'];

    public function maintenanceRequest(): BelongsTo { return $this->belongsTo(MaintenanceRequest::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}