<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Tenant extends Model
{
    protected $with = ['customer'];

    protected $fillable = [
        'customer_id',
        'room_id',
        'start_date',
        'end_date',
        'monthly_fee',
        'deposit',
        'payment_type',
        'notes',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_fee' => 'decimal:2',
        'deposit' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saved(function ($tenant) {
            if ($tenant->room) {
                $tenant->room->syncStatus();
            }
            if ($tenant->isDirty('room_id')) {
                $oldRoomId = $tenant->getOriginal('room_id');
                $oldRoom = Room::find($oldRoomId);
                if ($oldRoom) {
                    $oldRoom->syncStatus();
                }
            }
        });

        static::deleted(function ($tenant) {
            if ($tenant->room) {
                $tenant->room->syncStatus();
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getNameAttribute()
    {
        return $this->customer?->name;
    }

    public function getPhoneAttribute()
    {
        return $this->customer?->phone;
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function financeTransactions(): MorphMany
    {
        return $this->morphMany(FinanceTransaction::class, 'sourceable');
    }

    public function tenantPayments()
    {
        return $this->hasMany(TenantPayment::class)->orderBy('created_at', 'desc');
    }

    /**
     * Sisa hari kontrak (countdown).
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->status !== 'aktif') {
            return 0;
        }
        
        $today = Carbon::today();
        $endDate = Carbon::parse($this->end_date);
        
        if ($today->gt($endDate)) {
            return -$today->diffInDays($endDate);
        }
        
        return $today->diffInDays($endDate);
    }

    /**
     * Dapatkan warning level berdasarkan sisa hari kontrak
     * - Lewat tempo: negative
     * - Hari H: 0
     * - <= 3 hari: warning_danger
     * - <= 7 hari: warning_amber
     * - > 7 hari: safe
     */
    public function getReminderStatusAttribute(): string
    {
        $days = $this->days_remaining;

        if ($this->status !== 'aktif') {
            return 'selesai';
        }
        
        if ($days < 0) {
            return 'overdue';
        } elseif ($days == 0) {
            return 'today';
        } elseif ($days <= 3) {
            return 'danger'; // 3 hari
        } elseif ($days <= 7) {
            return 'warning'; // 7 hari
        }
        
        return 'normal';
    }
}
