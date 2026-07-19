<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    protected $fillable = ['room_number', 'price', 'status'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function activeTenant(): HasOne
    {
        return $this->hasOne(Tenant::class)->where('status', 'aktif');
    }

    public function syncStatus(): void
    {
        $hasActive = $this->activeTenant()->exists();
        if ($hasActive) {
            if ($this->status !== 'terisi') {
                $this->update(['status' => 'terisi']);
            }
        } else {
            if ($this->status === 'terisi') {
                $this->update(['status' => 'kosong']);
            }
        }
    }
}
