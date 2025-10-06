<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestMonitoring extends Model
{
    use SoftDeletes;

    protected $table = 'guest_monitoring';

    protected $fillable = [
        'guest_name',
        'contact_number',
        'entry_time',
        'exit_time',
        'status',
        'total_fee',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
        'total_fee' => 'decimal:2'
    ];

    public $timestamps = true;

    public function details()
    {
        return $this->hasMany(GuestMonitoringDetail::class, 'guest_monitoring_id');
    }

    public function discountApplications()
    {
        return $this->hasMany(DiscountApplication::class, 'guest_monitoring_id');
    }
}
