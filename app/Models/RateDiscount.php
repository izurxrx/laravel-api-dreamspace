<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateDiscount extends Model
{
    use SoftDeletes;

    protected $table = 'rate_discounts';

    public $timestamps = true;

    protected $fillable = [
        'rate_id',
        'discount_id',
    ];

    protected $casts = [
        'rate_id' => 'integer',
        'discount_id' => 'integer',
    ];  

    public function rate()
    {
        return $this->belongsTo(Rate::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
