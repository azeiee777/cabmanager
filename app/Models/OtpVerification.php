<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $table = 'otp_verifications';

    // 1. ADD identifier_hash HERE so Laravel allows it to be saved
    protected $fillable = [
        'identifier',
        'identifier_hash',
        'code',
        'expires_at',
        'verified',
    ];

    // 2. ADD the encrypted cast HERE so Laravel encrypts the email automatically
    protected $casts = [
        'identifier' => 'encrypted',
        'expires_at' => 'datetime',
        'verified' => 'boolean',
    ];

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }
}