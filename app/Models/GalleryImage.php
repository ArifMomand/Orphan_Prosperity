<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['image_path', 'caption'];

    public function imageUrl(): string
    {
        return asset($this->image_path);
    }
}
