<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'identifier',
        'identifier_hash', // <--- THIS WAS MISSING
        'password',
        'cab_number',
        'pin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'identifier' => 'encrypted', // <--- REQUIRED for automatic encryption
        ];
    }

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}