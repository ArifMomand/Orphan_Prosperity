<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PioneerApplication extends Model
{
    protected $fillable = [
        'pioneer_id',
        'full_name',
        'email',
        'phone',
        'city',
        'district',
        'experience',
        'heard_from',
        'availability',
        'skills',
        'motivation',
        'agree_background_check',
        'status',
    ];

    protected $casts = [
        'availability'            => 'array',
        'skills'                  => 'array',
        'agree_background_check'  => 'boolean',
    ];
}