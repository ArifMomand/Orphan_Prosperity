<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmanDonation extends Model
{
    protected $fillable = [
        'beneficiary_id', 'donor_name', 'donor_email', 'amount',
        'need', 'method', 'reference', 'note', 'donated_at', 'recorded_by',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'donated_at' => 'date',
    ];

    public const METHODS = [
        'donorbox'      => 'Donorbox',
        'bank_transfer' => 'Bank transfer',
        'cash'          => 'Cash',
        'other'         => 'Other',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(AmanId::class, 'beneficiary_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public static function needLabel(?string $need): string
    {
        return match (true) {
            $need === null, $need === 'general' => 'General support',
            $need === 'cash'                    => 'Direct cash',
            default                             => AmanId::NEEDS[$need][0] ?? $need,
        };
    }
}