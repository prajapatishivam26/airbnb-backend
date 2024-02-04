<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotelwishlist extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table= 'hotelwishlists';
    protected $fillable = [
       'hotel_id',
       'wishlist_id'
        ];
}
