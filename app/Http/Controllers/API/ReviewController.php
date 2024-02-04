<?php

namespace App\Http\Controllers\API;
// use Stripe\Review;
use App\Models\Hotel;
use App\Models\review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function store(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'user_id' => 'required|exists:users,id',
        'hotel_id' => 'required|exists:hotels,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:255',
    ]);

    try {
        // Create a new review
        $review = Review::create($validatedData);

        // Return a success response
        return response()->json(['message' => 'Review created successfully', 'review' => $review], 201);
    } catch (\Exception $e) {
        // Handle any exceptions, such as database errors
        return response()->json(['message' => 'Failed to create review', 'error' => $e->getMessage()], 500);
    }
}
// public function update(Request $request, Review $review)
// {
//     // Validate the incoming request data
//     $validatedData = $request->validate([
//         'rating' => 'required|integer|min:1|max:5',
//         'comment' => 'nullable|string|max:255',
//     ]);

//     try {
//         // Update the review with the validated data
//         $review->update([
//             'rating' => $validatedData['rating'],
//             'comment' => $validatedData['comment'],
//         ]);
        

//         // Reload the updated review data from the database
//         $review->refresh();

//         // Return a success response
//         return response()->json(['message' => 'Review updated successfully', 'review' => $review]);
//     } catch (\Exception $e) {
//         // Handle any exceptions, such as database errors
//         return response()->json(['message' => 'Failed to update review', 'error' => $e->getMessage()], 500);
//     }
// }
public function update(Request $request){
    $review = review::find($request->id);

    $review->update([
        'rating' => $request->rating,
           'comment' => $request->comment,
            
            
    ]);
    $review->save();
    return response()->json(['message'=>'Review Updated'],200);
}

// public function destroy($id)
// {
//     // Delete the review
//     $review->delete();

//     // Return a success response
//     return response()->json(['message' => 'Review deleted successfully']);

public function destroy($id)
{
    $review = review::find($id);
    if (!$review) {
        return response()->json(['message' => 'review  not found'], 404);
    }
    $review->delete();
    return response()->json(['message' => 'review deleted'], 200);
}

public function index(Hotel $hotel)
{
    // Get reviews for the specified hotel
    $reviews = $hotel->reviews;

    // Return the list of reviews as JSON
    return response()->json(['reviews' => $reviews]);
}


}
