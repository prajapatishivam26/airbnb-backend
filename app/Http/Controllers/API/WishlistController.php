<?php

namespace App\Http\Controllers\API;


use App\Models\User;


use App\Models\Hotel;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Models\hotelwishlist;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function addHotelToWishlist(Request $request)
{
    // Ensure the user is authenticated
    // $user = Auth::user();
    // if (!$user) {
    //     return response()->json(['message' => 'User not authenticated'], 401);
    // }

    // Validate the incoming request data (hotel_id)
    $request->validate([
        'hotel_id' => 'required|exists:hotels,id',
    ]);

    //Get the hotel by its ID
    $hotel = Hotel::find($request->hotel_id);

    if (!$hotel) {
        return response()->json(['message' => 'Hotel not found'], 404);
    }

    $user = User::find($request->user_id);
    if(!$user){
        return response()->json(['message' => 'user not found'],404);
    }
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Check if the user already has a wishlist
    $wishlist = Wishlist::where('user_id', $user->id)->first();

    // If the user doesn't have a wishlist, create a new one
    if (!$wishlist) {
        $wishlist = new Wishlist();
        $name = $request->input('name'); // You can set a default name for the wishlist or let the user specify it.
        $wishlist->name = $name;
        $wishlist->user_id = $user->id;
        $wishlist->save();
    }

   // Check if the hotel is already in the wishlist
if ($wishlist && $wishlist->hotels->contains($hotel->id)) {
    return response()->json(['message' => 'Hotel is already in the wishlist']);
}

// Associate the hotel with the user's wishlist
$wishlist->hotels()->attach($hotel);


return response()->json(['message' => 'Hotel added to wishlist'], 200);

}




public function index(Request $request)
{
    // Get the authenticated user
    // $user = auth()->user();
    $user = User::find($request->user_id);
    if(!$user){
        return response()->json(['message' => 'user not found'],404);
    }

    // Get all wishlists for the user
    $wishlists = Wishlist::where('user_id', $user->id)->first();
    $hotel= hotelwishlist::where('wishlist_id',$wishlists->id)->get();

    return response()->json(['wishlists' =>  $hotel], 200);
}



public function removeHotelFromWishlist(Request $request)
{
    // // Ensure the user is authenticated
    // $user = Auth::user();
    // if (!$user) {
    //     return response()->json(['message' => 'User not authenticated'], 401);
    // }

    // Validate the incoming request data (hotel_id and wishlist_id)
    $request->validate([
        'hotel_id' => 'required|exists:hotels,id',
        'wishlist_id' => 'required|exists:wishlists,id',
    ]);

    // Get the hotel and wishlist by their IDs
    $hotel = Hotel::find($request->hotel_id);
    $wishlist = Wishlist::find($request->wishlist_id);

    if (!$hotel || !$wishlist) {
        return response()->json(['message' => 'Hotel or wishlist not found'], 404);
    }

    // Check if the hotel is in the wishlist
    if (!$wishlist->hotels->contains($hotel->id)) {
        return response()->json(['message' => 'Hotel is not in the wishlist']);
    }

    // Detach the hotel from the wishlist
    $wishlist->hotels()->detach($hotel);

    return response()->json(['message' => 'Hotel removed from wishlist'], 200);
}




public function update(Request $request, Wishlist $wishlist)
{
    // Ensure the user is authorized to update the wishlist
    // $user = Auth::user();
    // if (!$user || $user->id !== $wishlist->user_id) {
    //     return response()->json(['message' => 'Unauthorized'], 401);
    // }

    // Validate the incoming request data
    $request->validate([
        'name' => 'required|string|max:255',
        // Add other fields to validate as needed
    ]);

    // Update the wishlist
    $wishlist->update([
        'name' => $request->input('name'),
        // Update other fields as needed
    ]);

    return response()->json(['message' => 'Wishlist updated successfully', 'wishlist' => $wishlist], 200);
}





}
