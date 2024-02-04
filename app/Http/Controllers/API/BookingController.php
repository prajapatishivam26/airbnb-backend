<?php

namespace App\Http\Controllers\API;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data here and create a new booking
        $booking = Booking::create($request->all());
        return response()->json(['message' => 'Booking created', 'data' => $booking], 201);
    }

    public function index()
    {
        // Retrieve and list bookings for hosts and guests
        $bookings = Booking::all();
        return response()->json(['data' => $bookings], 200);
    }

    // public function accept()
    // {
    //     // Accept a booking by changing its status
    //     $booking = Booking::findOrFail();
    //     $booking->update(['status' => 'accepted']);
    //     return response()->json(['message' => 'Booking accepted', 'data' => $booking], 200);
    // }
    public function accept($id)
{
    // Find the booking by ID
    $booking = Booking::findOrFail($id);

    // Check if the booking status is 'pending' before accepting
    if ($booking->status === 'pending') {
        // Update the booking status to 'accepted'
        $booking->update(['status' => 'accepted']);

        return response()->json(['message' => 'Booking accepted', 'data' => $booking], 200);
    } else {
        return response()->json(['message' => 'Booking cannot be accepted.'], 400);
    }
}

    public function cancel($id)
    {
        // Cancel a booking by changing its status
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Booking cancelled', 'data' => $booking], 200);
    }

    // public function cancel($id)
    // {
    //     // Find the booking by ID
    //     $booking = Booking::find($id);

    //     if (!$booking) {
    //         return response()->json(['message' => 'Booking not found'], 404);
    //     }

    //     // Check if the booking status is 'pending' or 'accepted' before canceling
    //     if ($booking->status === 'pending' || $booking->status === 'accepted') {
    //         // Update the booking status to 'cancelled'
    //         $booking->update(['status' => 'cancelled']);

    //         return response()->json(['message' => 'Booking cancelled', 'data' => $booking], 200);
    //     } else {
    //         return response()->json(['message' => 'Booking cannot be cancelled.'], 400);
    //     }
    // }

}
