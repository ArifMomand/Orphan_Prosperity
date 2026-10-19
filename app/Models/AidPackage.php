<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AidPackage extends Model
{
    public const CATEGORIES = [
        'Food', 'Shelter', 'Cleaning Supplies', 'Hygiene Kits',
        'School Supplies', 'Money', 'Health Support',
    ];

    public const CURRENCIES = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
    ];

    protected $fillable = [
        'title', 'category', 'price', 'currency', 'description', 'includes',
        'image_path', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(
            NeedyPerson::class,
            'aid_package_needy_person',
            'aid_package_id',
            'needy_person_id'
        );
    }

    /** "What's included" as a list (one item per line in the admin). */
    public function includesList(): array
    {
        return array_values(array_filter(
            array_map('trim', preg_split('/\R/', (string) $this->includes))
        ));
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    public function imageUrl(): string
    {
        return $this->image_path
            ? asset($this->image_path)
            : 'https://picsum.photos/seed/aid-' . $this->id . '/400/400';
    }

    public function priceLabel(): string
    {
        $symbol = self::CURRENCIES[$this->currency] ?? $this->currency . ' ';

        return $symbol . number_format((float) $this->price, 2);
    }
}
