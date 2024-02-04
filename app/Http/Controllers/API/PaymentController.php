<?php

namespace App\Http\Controllers\API;
use Stripe\Charge;
use Stripe\Stripe;
use App\Models\payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class PaymentController extends Controller
{
    public function charge(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $charge = Charge::create([
                'amount' => $request->amount * 100, // Convert to cents
                'currency' => 'usd',
                'source' => $request->stripeToken, // Stripe token obtained from the client-side
                'description' => 'Payment for booking on Airbnb'
            ]);

            // Payment was successful
            return response()->json(['message' => 'Payment successful']);
        } catch (\Exception $e) {
            // Payment failed
            return response()->json(['error' => 'Payment failed'], 400);
        }
    }



    public function verify(Request $request)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    try {
        // Retrieve the payment record by payment ID
        $payment = Payment::where('payment_id', $request->payment_id)->first();

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Verify the payment with Stripe using the payment ID
        $stripePayment = Charge::retrieve($payment->payment_id);

        if ($stripePayment->status === 'succeeded') {
            // Payment was successful
            $payment->update(['status' => 'verified']);
            return response()->json(['message' => 'Payment verified']);
        } else {
            // Payment failed
            return response()->json(['error' => 'Payment verification failed'], 400);
        }
    } catch (\Exception $e) {
        // Payment verification failed
        return response()->json(['error' => 'Payment verification failed'], 400);
    }
}



public function listPayments($userId)
{
    // Find the user by their ID
    $user = User::find($userId);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Retrieve payments associated with the user
    $payments = $user->payments;

    return response()->json(['payments' => $payments]);
}
}
