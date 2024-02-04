<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
    'first_name',
    'surname',
    'date_of_birth',
    'email',
    'password',
];
}
