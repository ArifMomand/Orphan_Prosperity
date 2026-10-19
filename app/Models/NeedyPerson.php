<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NeedyPerson extends Model
{
    protected $table = 'needy_people';

    public const CATEGORIES = [
        'Women', 'Children', 'Elderly', 'Orphan',
        'Disabled', 'Widow', 'Malnourished', 'Disaster Survivors',
    ];

    protected $fillable = [
        'name', 'category', 'age', 'family_size', 'story',
        'city', 'country', 'country_code',
        'image_path', 'is_verified', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function aidPackages(): BelongsToMany
    {
        return $this->belongsToMany(
            AidPackage::class,
            'aid_package_needy_person',
            'needy_person_id',
            'aid_package_id'
        );
    }

    /** The story split into paragraphs (separated by a blank line). */
    public function storyParagraphs(): array
    {
        return array_values(array_filter(
            preg_split('/\R{2,}/', trim((string) $this->story))
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
            : 'https://picsum.photos/seed/person-' . $this->id . '/400/500';
    }

    /** "Afg" style short name shown on the card, e.g. "Kunduz, Afg" */
    public function locationLabel(): string
    {
        $short = $this->country_code === 'AF' ? 'Afg' : $this->country;

        return trim(($this->city ? $this->city . ', ' : '') . $short);
    }

    /** Converts a 2-letter country code into a flag emoji. */
    public function flag(): string
    {
        $code = strtoupper((string) $this->country_code);

        if (strlen($code) !== 2) {
            return '';
        }

        return mb_chr(127397 + ord($code[0])) . mb_chr(127397 + ord($code[1]));
    }
}
