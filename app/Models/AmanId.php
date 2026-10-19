<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmanId extends Model
{
    protected $fillable = [
        'uuid', 'aman_id', 'user_id',
        'first_name', 'last_name', 'country', 'province', 'district', 'gender',
        'primary_phone', 'secondary_phone',
        'registered_for', 'categories', 'needs',
        'cash_need', 'cash_amount', 'visibility', 'status',
            'reviewed_by', 'reviewed_at', 'rejection_reason',   // NEW

    ];

    protected $casts = [
        'categories'  => 'array',
        'needs'       => 'array',
        'cash_need'   => 'boolean',
        'cash_amount' => 'decimal:2',
            'reviewed_at' => 'datetime',                         // NEW

    ];
       public function reviewer()                               // NEW
{
    return $this->belongsTo(User::class, 'reviewed_by');
}
public function donations()
{
    return $this->hasMany(AmanDonation::class, 'beneficiary_id');
}
    public const PROVINCES = [
        'Badakhshan', 'Badghis', 'Baghlan', 'Balkh', 'Bamyan', 'Daykundi', 'Farah', 'Faryab',
        'Ghazni', 'Ghor', 'Helmand', 'Herat', 'Jowzjan', 'Kabul', 'Kandahar', 'Kapisa', 'Khost',
        'Kunar', 'Kunduz', 'Laghman', 'Logar', 'Maidan Wardak', 'Nangarhar', 'Nimruz', 'Nuristan',
        'Paktia', 'Paktika', 'Panjshir', 'Parwan', 'Samangan', 'Sar-e Pol', 'Takhar', 'Uruzgan', 'Zabul',
    ];

    public const GENDERS = ['Male', 'Female'];

    public const REGISTERED_FOR = [
        'self'   => 'Myself',
        'family' => 'A family member',
        'other'  => 'Someone else',
    ];

    public const CATEGORIES = [
        'orphan'    => 'Orphan',
        'disabled'  => 'Disabled',
        'widow'     => 'Widow',
        'malnourished' => 'Malnourished',
        'disaster'  => 'Disaster Survivors',
        'chronic'   => 'Chronic illness patient',
        'caretaker' => 'Family caretaker',
        'displaced' => 'Forcibly displaced person',
        'water'     => 'Water shortage',
        'electricity' => 'With no electricity',
    ];

    // slug => [label, group]  (edit freely, or move to a table later)
    public const NEEDS = [
        'family-food'      => ['Emergency Family Food Package', 'Food'],
        'large-family-food'=> ['Large Family Food Package', 'Food'],
        'malnutrition'     => ['Lifesaving Malnutrition Package', 'Food'],
        'bread-widows'     => ['Monthly Bread for Widows', 'Food'],
        'winter-shelter'   => ['Winter Shelter Kit', 'Shelter'],
        'blankets'         => ['Warm Blankets Package', 'Shelter'],
        'cleaning'         => ['Household Cleaning Supplies', 'Cleaning Supplies'],
        'hygiene'          => ['Family Hygiene Kit', 'Hygiene Kits'],
        'school'           => ['School Supplies Package', 'School Supplies'],
    ];

    public const NEED_GROUPS = ['Food', 'Shelter', 'Cleaning Supplies', 'Hygiene Kits', 'School Supplies'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Use uuid in URLs so IDs can't be guessed one by one
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // "Sediqa Sa***" – protects the beneficiary's privacy on the card
    public function getMaskedNameAttribute(): string
    {
        $last = mb_substr($this->last_name, 0, 2);
        return $this->first_name . ' ' . $last . '***';
    }
    // QR code image (SVG data URI) that opens this person's public page
public function getQrDataUriAttribute(): string
{
    $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
        ->size(200)
        ->margin(0)
        ->errorCorrection('M')
        ->generate(route('aman-id.show', $this));

    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

    public static function formatId(int $id): string
    {
        return 'A-' . str_pad((string) $id, 7, '0', STR_PAD_LEFT);
    }
}