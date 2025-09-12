<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityType extends Model
{
    protected $table = 'facility_types';

    protected $fillable = [
        'name', 
        'description', 
        'is_active'
    ];

    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at'; 

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'created_at' => 'datetime',
        ];
    }
}
