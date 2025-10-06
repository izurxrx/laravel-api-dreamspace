<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use SoftDeletes;

    protected $table = 'discounts';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'type',
        'discount_rate',
        'rate_type',
        'discount_scope',
        'start_date',
        'end_date',
        'description'
    ];

    protected $casts = [
        'discount_rate' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function rates()
    {
        return $this->belongsToMany(Rate::class, 'rate_discounts');
    }

    public function applications()
    {
        return $this->hasMany(DiscountApplication::class);
    }

    public function rateDiscounts()
    {
        return $this->hasMany(RateDiscount::class);
    }
}
