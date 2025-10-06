<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\GuestMonitoring;
use App\Models\Discounts;

class DiscountApplication extends Model
{
    use SoftDeletes;

    protected $table = 'discount_applications';

    public $timestamps = true;

    protected $fillable = [
        'guest_monitoring_id',
        'discount_id',
        'custom_name',
        'type',
        'discount_rate',
        'applied_value',
    ];

    protected $casts = [
        'guest_monitoring_id' => 'integer',
        'discount_id' => 'integer',
        'discount_rate' => 'decimal:2',
        'applied_value' => 'decimal:2'
    ];

    public function guestMonitoring()
    {
        return $this->belongsTo(GuestMonitoring::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
