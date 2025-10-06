<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestType extends Model
{
    use SoftDeletes;
    protected $table = 'guest_types';
    
    public $timestamps = true;

    protected $fillable = [
        'name', 
        'description',
    ];

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
