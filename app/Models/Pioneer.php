<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pioneer extends Authenticatable
{
    protected $fillable = ['name', 'username', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function causes()
    {
        return $this->hasMany(Cause::class);
    }

    /** The volunteer application submitted together with this account. */
    public function application(): ?PioneerApplication
    {
        return PioneerApplication::where('pioneer_id', $this->id)->latest()->first();
    }

    public function isVerified(): bool
    {
        return $this->application()?->status === 'verified';
    }
}