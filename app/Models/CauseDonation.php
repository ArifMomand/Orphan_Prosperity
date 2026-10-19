<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauseDonation extends Model
{
    protected $fillable = [
        'cause_id', 'donor_name', 'donor_email', 'amount',
        'method', 'reference', 'note', 'donated_at', 'recorded_by',
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

    protected static function booted(): void
    {
        static::saved(fn (CauseDonation $d) => $d->cause?->recalculateTotals());
        static::deleted(fn (CauseDonation $d) => $d->cause?->recalculateTotals());
    }

    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}