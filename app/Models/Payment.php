<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Payment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['invoice_id','amount','method','transaction_id','reference','paid_by','receipt_pdf','payment_date','notes'];

    protected function casts(): array { return ['amount'=>'decimal:2','payment_date'=>'date']; }

    public function getActivitylogOptions(): LogOptions { return LogOptions::defaults()->logFillable(); }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method){'cash'=>'نقداً','bank_transfer'=>'تحويل بنكي','moyasar'=>'ميسر','check'=>'شيك',default=>$this->method};
    }

    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function paidBy(): BelongsTo { return $this->belongsTo(User::class,'paid_by'); }
}