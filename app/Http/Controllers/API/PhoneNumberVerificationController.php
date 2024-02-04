<?php

namespace App\Http\Controllers\API;

use App\Model;
use Illuminate\Http\Request;
use App\PhoneNumberVerification;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class PhoneNumberVerificationController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Generate a random 6-digit verification code
        $verificationCode = strval(mt_rand(100000, 999999));

        // Store the verification request in the database
        PhoneNumberVerification::create([
            'country_code' => $request->country_code,
            'phone_number' => $request->phone_number,
            'verification_code' => $verificationCode,
        ]);

        // Send the verification code to the user (you can implement this part)

        return response()->json([
            'message' => 'Verification code sent successfully',
        ]);
    }

    public function verifyPhoneNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|string',
            'phone_number' => 'required|string',
            'verification_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Check if the verification code matches
        $verification = PhoneNumberVerification::where([
            'country_code' => $request->country_code,
            'phone_number' => $request->phone_number,
            'verification_code' => $request->verification_code,
        ])->first();

        if (!$verification) {
            return response()->json([
                'message' => 'Invalid verification code',
            ], 401);
        }

        // Mark the phone number as verified
        $verification->update(['is_verified' => true]);

        return response()->json([
            'message' => 'Phone number verified successfully',
        ]);
    }
}
