<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Hasfactory, Notifiable;
    protected $fillable = [
        'first_name',
         'surname',
        'd_o_b',
        'email',
        'login_type',
        'phone_number',
        'password',
        'address',
        'img',
    ];

    // Additional properties and relationships can be defined here

    // Define relationships, if any
    // public function someOtherModel()
    // {
    //     return $this->hasMany(SomeOtherModel::class);
    // }
}