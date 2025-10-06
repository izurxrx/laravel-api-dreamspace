<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestMonitoringDetail extends Model
{
    use SoftDeletes;

    protected $table = 'guest_monitoring_details';

    public $timestamps = true;

    protected $fillable = [
        'guest_monitoring_id',
        'rate_id',
        'guest_count',
        'applied_rate',
    ];

    protected $casts = [
        'guest_monitoring_id' => 'integer',
        'rate_id' => 'integer',
        'guest_count' => 'integer',
        'applied_rate' => 'decimal:2'
    ];

    public function rate()
    {
        return $this->belongsTo(Rate::class);
    }

    public function facility()
    {
        return $this->hasOneThrough(Facility::class, Rate::class, 'id', 'id', 'rate_id', 'facility_id');
    }
}
