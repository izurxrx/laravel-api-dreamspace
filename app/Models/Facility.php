<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'facilities';

    protected $fillable = [
        'name',
        'facility_type_id',
        'description',
        'quantity',
        'expected_capacity',
        'max_capacity',
        'is_active',
        'booking_types',
        'cutoff_time',
        'requires_accommodation',
        'time_based_booking',
        'day_based_booking',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'requires_accommodation' => 'boolean',
            'time_based_booking' => 'boolean',
            'day_based_booking' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'cutoff_time' => 'string',
        ];
    }

    public function facilityType()
    {
        return $this->belongsTo(FacilityType::class);
    }
}
