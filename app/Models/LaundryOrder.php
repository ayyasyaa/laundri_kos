<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class LaundryOrder extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'service_id',
        'weight',
        'price',
        'additional_fees',
        'total_price',
        'paid_amount',
        'payment_status',
        'payment_method',
        'status',
        'delivery_type',
        'estimation_date',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'additional_fees' => 'decimal:2',
        'total_price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'estimation_date' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(LaundryService::class, 'service_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'laundry_order_id');
    }

    public function financeTransactions(): MorphMany
    {
        return $this->morphMany(FinanceTransaction::class, 'sourceable');
    }

    // Helper to get pickup delivery details
    public function pickup()
    {
        return $this->deliveries()->where('type', 'pickup')->first();
    }

    public function delivery()
    {
        return $this->deliveries()->where('type', 'delivery')->first();
    }
}
