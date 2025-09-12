<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntranceFee extends Model
{
    protected $table = 'entrance_fees';

    protected $fillable = [
        'guest_type',
        'fee_amount',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fee_amount' => 'decimal:2',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
