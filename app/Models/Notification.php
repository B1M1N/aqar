<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','type','title','body','data','read_at'];

    protected function casts(): array { return ['data'=>'array','read_at'=>'datetime']; }

    public function scopeUnread(Builder $q): Builder { return $q->whereNull('read_at'); }
    public function scopeRead(Builder $q): Builder { return $q->whereNotNull('read_at'); }
    public function scopeForUser(Builder $q, int $userId): Builder { return $q->where('user_id',$userId); }

    public function getIsReadAttribute(): bool { return !is_null($this->read_at); }
    public function markAsRead(): void { if(is_null($this->read_at)){$this->update(['read_at'=>now()]);} }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}