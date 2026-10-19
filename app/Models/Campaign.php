<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'video_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Higher priority first, then newest first. */
    public function scopeOrdered($query)
    {
        return $query->orderByDesc('sort_order')->orderByDesc('id');
    }

    public function imageUrl(): string
    {
        return asset($this->image_path);
    }

    /** Pulls the 11-character video ID out of any YouTube link. */
    public static function extractYoutubeId(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        // Someone pasted just the ID
        if (preg_match('/^[\w-]{11}$/', $url)) {
            return $url;
        }

        $patterns = [
            '~youtu\.be/([\w-]{11})~i',
            '~youtube(?:-nocookie)?\.com/(?:watch\?(?:[^#]*&)?v=|embed/|shorts/|live/|v/)([\w-]{11})~i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $m)) {
                return $m[1];
            }
        }

        return null;
    }

    public function youtubeId(): ?string
    {
        return self::extractYoutubeId($this->video_url);
    }

    public function isShort(): bool
    {
        return (bool) preg_match('~youtube\.com/shorts/~i', (string) $this->video_url);
    }
}