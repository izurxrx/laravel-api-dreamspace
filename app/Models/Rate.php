<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use SoftDeletes;

    protected $table = 'rates';

    public $timestamps = true;

    protected $fillable = [
        'rate_category',
        'facility_id',
        'guest_type_id',
        'rate_type',
        'duration_hours',
        'time_period',
        'base_rate',
        'description',
        'status',
        'extension_fee',
        
    ];

    protected $casts = [
        'facility_id' => 'integer',
        'guest_type_id' => 'integer',
        'duration_hours' => 'integer',
        'base_rate' => 'decimal:2',
        'extension_fee' => 'decimal:2'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function guestType()
    {
        return $this->belongsTo(GuestType::class);
    }

    public function rateDiscounts()
    {
        return $this->hasMany(RateDiscount::class);
    }

    public function guestMonitoringDetails()
    {
        return $this->hasMany(GuestMonitoringDetail::class);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'rate_discounts');
    }
}
