<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cause extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'location',
        'goal', 'raised', 'donors', 'opening_raised', 'opening_donors', 'image_path',
        'summary', 'story', 'image', 'pioneer_id', 'orphan_name', 'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Cause $cause) {
            if (empty($cause->slug)) {
                $cause->slug = static::uniqueSlug($cause->title);
            }
        });

        static::updating(function (Cause $cause) {
            if ($cause->isDirty('title') && ! $cause->isDirty('slug')) {
                $cause->slug = static::uniqueSlug($cause->title, $cause->id);
            }
        });
    }

    protected static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function percentFunded(): int
    {
        return $this->goal > 0 ? min(100, (int) round($this->raised / $this->goal * 100)) : 0;
    }

    public function imageUrl(): string
    {
        if ($this->image && str_starts_with($this->image, 'causes/')) {
            return asset('storage/' . $this->image);
        }

        return $this->image_path
            ? asset($this->image_path)
            : 'https://picsum.photos/seed/' . $this->slug . '/600/400';
    }

    public function pioneer()
    {
        return $this->belongsTo(Pioneer::class);
    }

    public function donations()
    {
        return $this->hasMany(CauseDonation::class);
    }

    // raised = starting amount + recorded donations; donors = starting donors + number of donations
    public function recalculateTotals(): void
    {
        $sum   = (float) $this->donations()->sum('amount');
        $count = $this->donations()->count();

        $this->forceFill([
            'raised' => (int) round($this->opening_raised + $sum),
            'donors' => (int) ($this->opening_donors + $count),
        ])->saveQuietly();
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}