<?php
namespace App\Http\Controllers\API;

use App\Models;
// use App\Models\user;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;

class AuthController extends Controller
{
    public function login(Request $request)
{
    // Validate input (email or phone_number)
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'phone_number' => 'required',
        


    ]);

   

     if($request->has('email')) {
        // Email-based registration or login
        $email = $request->input('email');

        // Check if the user with the provided email exists
        $user = User::where('email', $email)->first();

        if (!$user) {
            // User doesn't exist, create a new user
            $user = User::create([
                'email' => $email,
                // 'password' => $password, // Generate a random password
                
            ]);

            return response()->json(['message' => 'Email registered successfully', 'data' => $user], 200);
        }

        return response()->json(['message' => 'Email login successfully', 'data' => $user], 200);
    } elseif ($request->has('phone_number')) {
        // Phone number-based registration or login

        // Generate an OTP
        $otp = rand(10000, 99999);

        $phoneNumber = $request->input('phone_number');

        // Check if the user with the provided phone number exists
        $user = user::where('phone_number', $phoneNumber)->first();

        if (!$user) {
            // User doesn't exist, create a new user
            $user = User::create([
                'phone_number' => $phoneNumber,
                'otp' => $otp,
            ]);

            return response()->json(['message' => 'Phone number registered successfully', 'data' => $user], 200);
        }

        // Update the existing user's OTP
        $user->update(['otp' => $otp]);

        return response()->json(['message' => 'Phone number login successfully', 'data' => $user], 200);
    } else {
        return response()->json(['error' => 'Invalid input'], 400);
    }
}
    public function verifyotp(Request $request){
        $user = user::find($request->id);
        if(!$user){
            return response()->json(['message'=>'Something Wents Wrong Please Resend Otp'],300);
        }else{
            if($user->otp===$request->otp){
                return response()->json(['message'=>'otp verified','data'=>$user],200);

            }else{
                return response()->json(['message'=>'Wrong otp'],300);

            }
        }

    }
   




        public function emaillogin(Request $request){
            $user = User::where('email', '=', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                // Authentication successful
                return response()->json(['message' => 'Login successful', 'user' => $user], 200);
            } else {
                // Password does not match
                return response()->json(['error' => 'Password does not match'], 401);
            }
        } else {
            // User not found
            return response()->json(['error' => 'Email not registered'], 404);
        }
    }
    // public function updateprofile(Request $request, $id) {
    //     $user = User::find($id);
    
    //     if (!$user) {
    //         return response()->json(['message' => 'User not found'], 404);
    //     }
    
    //     // Validate the request data
    //     $validatedData = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:8', // You can add more validation rules here
    //         'phone_number' => 'required',
    //         'role' => 'required',
    //     ]);
    
    //     // Update the user's profile
    //     $user->update($validatedData);
    
    //     return response()->json(['message' => 'Profile Updated'], 200);
    // }
    
        public function updateprofile(Request $request, $id) {
        $users = User::find($id);
    
        if (!$users) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Update the user's profile
        $users->update([
            // 'first_name' => $request->first_name,
            'email' => $request->email,
            'email_verified_at' => $request->email_verified_at,
            'password' => Hash::make($request->password), // Use the hashed password
            'login_type' => $request->login_type,
            'remember_token' => $request->rememberToken,
            'device_token' => $request->device_token,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'description' => $request->description,
        
            // 'profile_image' => $request->profile_image,
        ]);
    
        return response()->json(['message' => 'Profile Updated'], 200);
    }
    

    // public function updateprofile(Request $request, $id){
    //     $user = User::find($id);
        
    
    //     // if (!$users) {
    //     //     return response()->json(['message' => 'User not found'], 404);
    //     // }
    
    //     $user->update([
    //         'first_name' => $request->first_name,
    //         'surname' => $request->surname,
    //         'd_o_b' => $request->date_of_birth,
    //         'email' => $request->email,
    //         'login_type' => $request->login_type,
    //         'phone_number' => $request->phone_number,
    //         'password' => Hash::make($request->password),
    //         'address' => $request->address,
    //         'img' => $request->img,
    //     ]);
    
    //     return response()->json(['message' => 'Profile Updated'], 200);
    // }
    
    public function uploadImage(Request $request)
    {
    
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
       
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path(), $imageName);
            $imagePath = public_path()."/" . $imageName;
            print_r($imagePath);
            return response()->json(['message'=>'Image uploaded  successfully ','data'=>$imagePath],200);
        }
    
        return response()->json(['message'=>'Something went Wrong'],300);;
    }


   public function socaillogin(Request $request){
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'login_type' =>'required',
        'name'=>'required',


    ]);
    if($validator->fails()){
        return response()->json([
            'message'=>'Something went wrong '
        ],300);
    }
    $user = User::where('email',$request->input('email'))->first();
    if(!$user){
        $user = user::create([
            'email'=>$request->email,
            'login_type'=> $request->login_type,
            'name'=>$request->name
        ]);

        $user->save();
       return response()->json([
            'message'=>'Welcome','data'=>$user
        ],200);

    }
    else{
        return response()->json([
            'message'=>'Welcome Back','data'=>$user
        ],200);
    }
}


public function changemode(Request $request)
{
    // Validation: Check if 'id' and 'active_as' are provided and valid.
    $this->validate($request, [
        'id' => 'required|integer|exists:users,id',
        'active_as' => 'required|string', // Adjust the validation rule for 'active_as' as needed.
    ]);

    // Find the user by ID
    $user = User::find($request->id);

    if (!$user) {
        // User not found
        return response()->json(['message' => 'User not found'], 404);
    }

    // Update the 'active_as' attribute
    $user->update(['active_as' => $request->active_as]);

    // Response with a success message and updated user data
    return response()->json(['message' => 'Mode changed successfully', 'data' => $user], 200);
}
}


    // public function switchToHost(Request $request)
    // {
    //     $user = Auth::user();

    //     if ($user !== null && $user->hasRole('host')) {
    //         $hostRole = Role::where('name', 'host')->first();
    //         $user->roles()->attach($hostRole);
    //     }

    //     return response()->json(['message' => 'Switched to host role'], 200);
    // }

    // public function switchToHost(Request $request, $userId)
    // {
    //     // Find the user by ID
    //     $user = User::find($userId);
    
    //     if ($user !== null) {
    //         // Check if the user already has the "host" role
    //         if (!$user->hasRole('host')) {
    //             // Find the "host" role in the database
    //             $hostRole = Role::where('name', 'host')->first();
    
    //             if ($hostRole !== null) {
    //                 // Attach the "host" role to the user
    //                 $user->roles()->attach($hostRole);
    
    //                 return response()->json(['message' => 'Switched to host role'], 200);
    //             } else {
    //                 return response()->json(['error' => 'Role "host" not found'], 404);
    //             }
    //         } else {
    //             return response()->json(['message' => 'User already has "host" role'], 200);
    //         }
    //     } else {
    //         return response()->json(['error' => 'User not found'], 404);
    //     }
    // }

    // public function switchToUser(Request $request)
    // {
    //     $user = Auth::user();

    //     if ($user->hasRole('host')) {
    //         $hostRole = Role::where('name', 'host')->first();
    //         $user->roles()->detach($hostRole);
    //     }

    //     return response()->json(['message' => 'Switched to user role'], 200);
    // }


       
   


//     public function redirectToGoogle()
// {
//     return Socialite::driver('google')->redirect();
// }

// public function handleGoogleCallback()
// {
//     try {
//         $googleUser = Socialite::driver('google')->user();
//     } catch (\Exception $e) {
//         return redirect('/login')->with('error', 'Google authentication failed.');
//     }

//     // Check if a user with the Google email already exists in your database
//     $user = User::where('email', $googleUser->getEmail())->first();

//     if (!$user) {
//         // User doesn't exist, create a new user using Google data
//         $user = User::create([
//             'name' => $googleUser->getName(),
//             'email' => $googleUser->getEmail(),
//             // Add other user data as needed
//         ]);
//     }

//     // Log in the user
//     auth()->login($user);

//     // Redirect to a protected area of your application or respond with a JWT token
//     return redirect('/dashboard');
// }











    // public function socialLogin(Request $request)
    // {
    //     // Validate the incoming request data
    //     $request->validate([
    //         'social_id' => 'required',
    //         'social_type' => 'required',
    //         'device_token' => 'required',
    //         'email' => 'required_without:name',
    //         'name' => 'required_without:email',
    //     ]);

    //     // Check if a user with the provided social_id exists
    //     $user = User::where('social_id', $request->social_id)->first();

    //     if ($user) {
    //         if ($user->is_deleted === 'Y') {
    //             return response()->json(['message' => 'Your account has been deleted.'], 400);
    //         }

    //         // Generate and update the JWT token
    //         $token = JWTAuth::fromUser($user);
    //         $user->token = $token;

    //         // Update the device token
    //         $user->device_token = $request->device_token;
    //         $user->save();

    //         // Retrieve and return additional user data ()
    //          $user->loadCount();
    //          return response()->json(['user' => $user, 'message' => 'Welcome.'], 200);
    //     } else {
    //         // User doesn't exist, create a new user
    //         $user = new User([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'email_verify' => 1,
    //             'image' => '',
    //             'phone' => '',
    //             'country_code' => '',
    //             'phone_no' => '',
    //             'gender' => '',
    //             'country' => $request->country,
    //             'dob' => '',
    //             'end_date' => '',
    //             'share_count' => 0,
    //             'is_subscribed' => 'free',
    //             'is_deleted' => 'N',
    //             // 'plan_id' => '',
    //             // 'plan_name' => '',
    //             // 'last_login' => '',
    //             // 'last_login_time' => '',
    //             // 'plan_type' => '',
    //             'social_type' => $request->social_type,
    //             'social_id' => $request->social_id,
    //             'login_type' => 'social',
    //             'device_token' => $request->device_token,
    //             'updated_at' => now(),
    //             'created_at' => now(),
    //         ]);
    //         $user->save();

    //         // Generate a JWT token for the new user
    //         $token = JWTAuth::fromUser($user);
    //         $user->token = $token;

    //         // Retrieve and return additional user data ()
    //         $user->loadCount();
    //         return response()->json(['user' => $user, 'message' => 'Welcome.'], 200);
    //     }
    // }


                 
                

        


//     public function login(Request $request)
//     {
//         // Validate phone number
//         $validator = Validator::make($request->all(), [
//             'phone_number' => 'required|phone:AUTO,US',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['error' => 'Invalid phone number'], 400);
//         }

//         // Check if the phone number exists
//         $user = User::where('phone_number', $request->input('phone_number'))->first();

//         if (!$user) {
//             return response()->json(['message' => 'Phone number not found'], 404);
//         }

//         // Generate and send OTP for login (you can use the laravel-otp package as shown in the previous answer)

//         // Redirect to the login page or send an OTP to the user for verification
//         // and handle the verification process in your frontend

//         return response()->json(['message' => 'OTP sent for login'], 200);
     
 
 

    

