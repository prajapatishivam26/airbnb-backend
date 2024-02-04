<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumberVerification extends Model
{
    protected $fillable = [
        'country_code',
        'phone_number',
        'verification_code',
        'is_verified',
    ];
}
