<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; // Import Hash for password hashing
use App\Models\User; // Import the User model

class UserController extends Controller
{
    public function create(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:101',
            'email' => 'required|email|unique:users|max:101',
            'email_verified_at' => 'nullable|date', // Allow nullable date for email_verified_at
            'password' => 'required|string|max:101', // Removed Hash::make here
            'login_type' => 'required|string|max:101',
            'remember_token' => 'string|max:101', // Allow nullable remember_token
            'device_token' => 'required|string|max:101',
            'phone_number' => 'required|digits:10',
            'description' => 'required|string|max:101',
            // 'profile_image' => 'string|max:255', // Assuming it's a file path or URL
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Hash the password before creating the user
        $hashedPassword = Hash::make($request->password);

        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'email' => $request->email,
            'email_verified_at' => $request->email_verified_at,
            'password' => $hashedPassword, // Use the hashed password
            'login_type' => $request->login_type,
            'remember_token' => $request->rememberToken,
            'device_token' => $request->device_token,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'description' => $request->description,
        
            // 'profile_image' => $request->profile_image,
        ]);

        if ($user) {
            return response()->json([
                'status' => 200,
                'message' => 'User created successfully.',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'User creation failed.',
            ], 500);
        }
    }



public function show(){
    $user=User::all();
    if($user->count() > 0){
        return response()->json([
            'status' => 200,
            'user' => $user 
        ], 200);
    }else{
        return response()->json([
            'status'=>404,
            'message'=>'No users found!'
        ], 404);
     }
        
    }


    public function updateProfile(Request $request, $id) {
        // Find the user
        $user = User::find($id);
    
        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|min:8', // Optional, update only if provided
            'profile_image' => 'sometimes|image|mimes:jpeg,png|max:2048', // Optional, image upload
            // Add other fields you want to update
        ]);
    
        // Handle profile image upload (if provided)
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = 'profile_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('profile_images'), $imageName);
            $user->profile_image = $imageName;
        }
    
        // Update user data
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        
        // Update password (if provided)
        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
    
        // Save changes
        $user->save();
    
        return response()->json(['message' => 'Profile Updated'], 200);
    }
    
}






