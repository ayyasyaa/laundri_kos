<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FinanceTransaction extends Model
{
    protected $fillable = [
        'type',
        'category',
        'date',
        'amount',
        'payment_method',
        'notes',
        'sourceable_type',
        'sourceable_id'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function sourceable(): MorphTo
    {
        return $this->morphTo();
    }
}
