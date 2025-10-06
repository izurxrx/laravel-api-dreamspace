<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;

    protected $table = 'facilities';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'facility_type_id',
        'description',
        'quantity',
        'expected_capacity',
        'max_capacity',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'expected_capacity' => 'integer',
        'max_capacity' => 'integer'
    ];

    public function facilityType()
    {
        return $this->belongsTo(FacilityType::class);
    }

    public function guestMonitoringDetails()
    {
        return $this->hasMany(GuestMonitoringDetail::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
