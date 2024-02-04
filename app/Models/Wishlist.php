<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        // Add other fields as needed
    ];



    public function hotels()
{
    return $this->belongsToMany(Hotel::class, 'hotelwishlists');
}


}

