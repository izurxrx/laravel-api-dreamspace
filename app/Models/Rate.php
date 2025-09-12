<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'rates';

    protected $fillable = [
        'rate_name',
        'rate_category',
        'facility_id',
        'rate_type',
        'time_period',
        'base_rate',
        'duration_hours',
        'duration_type',
        'applicable_hours',
        'max_booking_time',
        'description',
        'status',
        'extension_fee',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_rate' => 'decimal:2',
            'duration_hours' => 'decimal:2',
            'extension_fee' => 'decimal:2',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
