<?php

namespace App\Http\Controllers\API;

use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class HotelSearchController extends Controller
{
    public function search(Request $request)
    {

        $query = Hotel::query();
      

        if ($request->has('location')) {
            $location = $request->location;
            
            // Search for hotels with matching city or address
            $query->where('city', 'like', '%' . $location . '%');
                
               
        }
        if ($request->has('category')) {
            $category = $request->category;
            $query->where('category', $category);
        }

        // You can add more search criteria here, such as price range, rating, etc.

        $hotels = $query->get();
       

        return response()->json(['data' => $hotels], 200);
    }

}
