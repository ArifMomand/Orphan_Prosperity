<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectLocation extends Model
{
    protected $fillable = ['name', 'description', 'x_percent', 'y_percent'];
}
