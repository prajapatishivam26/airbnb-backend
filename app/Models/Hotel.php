<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    
    protected $fillable = [
    'name', 
    'description',
     'rating', 
     'price',
     'address',
     'city',
     'state',
     'zip_code',
     'country',
     'total_rooms',
      'guests_per_room',
      'bedrooms',
      'bathrooms',
      'category',
      'is_featured',
      'is_approved',
            



    ];
    public function wishlists()
{
    return $this->belongsToMany(Wishlist::class, 'hotelwishlists');
}

    }
