<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSheet extends Model
{
    protected $fillable = [
        'registration_number',
        'name',
        'car_type',
        'used_materials',
        'services',
        'tire_brand',
        'tire_size',
        'store',
        'store_qty',
        'store_tire',
        'store_wheel',
        'comment',
        'is_closed',
        'closed_at',
    ];

    protected $casts = [
        'used_materials' => 'array',
        'services' => 'array',
        'is_closed' => 'boolean',
        'store' => 'boolean',
        'store_tire' => 'boolean',
        'store_wheel' => 'boolean',
        'store_qty' => 'integer',
        'closed_at' => 'datetime',
    ];
}
