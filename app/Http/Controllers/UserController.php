<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use PharIo\Manifest\Email;


class UserController extends Controller
{
   

    public function register(Request $request)
    {
        try {
            $incomingFields = $request->validate([
                'name' => ['required', 'min:3', 'max:15', Rule::unique('users', 'name'), 'regex:/^[\p{L} ]+$/u'],
                'email' => ['required', 'email', 'regex:/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/',
                    'min:5', 'max:30', Rule::unique('users', 'email')],
                'phone' => ['required', 'min:5', 'max:15', 'regex:/^[0-9]+$/'],
                'password' => ['required', 'min:8', 'max:255'],
                'profile_image.*' => ['nullable', 'image|mimes:jpeg,png,jpg,gif|max:2048'],
            ]);
    
            
            $incomingFields['profile_image'] = 'no_image';
    
           
            if ($request->hasFile('profile_image')) {
                $profileImageName = uniqid('profile_image_') . '.' . $request->file('profile_image')->getClientOriginalExtension();
                $request->file('profile_image')->storeAs('public/profile_images', $profileImageName);
                $incomingFields['profile_image'] = '/storage/app/public/profile_images/' . $profileImageName;
            }
    
            $incomingFields['password'] = bcrypt($incomingFields['password']);
            $user = User::create($incomingFields);
    
            $accessToken = $user->createToken('myapptoken')->plainTextToken;
    
            $response = response()->json([
                'message' => 'Registration successful',
                'user' => $user,
                'access_token' => $accessToken,
                'authorization' => 'Bearer ' . $accessToken,
            ], 201);
    
            return $response;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    

    public function login(Request $request)
{
    try {
        $incomingFields = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $incomingFields['email'], 'password' => $incomingFields['password']])) {
            
            $user = User::where('email',$incomingFields['email'])->first();
         
            $accessToken = $user->createToken('myapptoken')->plainTextToken;

         
            session(['user_id' => $user->id, 'access_token' => $accessToken]);

        
            return response()->json(['message' => 'Login successful', 'access_token' => $accessToken], 200);
        }

        return response()->json(['error' => 'Invalid login credentials'], 401);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



    
        public function logout(Request $request)
    {
        try {
            Auth::logout();

            return response()->json(['message' => 'Logout successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    
    public function showProfile(Request $request)
{
    $request->headers->get('Authorization'); 
    try {
        
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        
        $user = Auth::user();
        $userPosts = Post::where('user_id', $user->id)->get();

        return response()->json(['user' => $user, 'userPosts' => $userPosts], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



public function updateProfile(Request $request)
{
    /** @var \App\Models\User $user **/
   
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

      
        $request->validate([
            'name' => ['required', 'min:3', 'max:15', Rule::unique('users', 'name')->ignore($user->id), 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'regex:/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'min:5', 'max:15', 'regex:/^[0-9]+$/'],
            'password' => ['nullable', 'min:8', 'max:255'], 
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

       
        if ($request->hasFile('profile_image')) {
            $imageName = time() . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $request->file('profile_image')->storeAs('public/profile_images', $imageName);
            $user->profile_image = '/storage/app/public/profile_images/' . $imageName;
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);

}











    



    /* 
    public function updateProfileImage(Request $request)
{
    try {
        $user = Auth::user();

        $request->validate([
            'profile_image' => ['required', 'image|mimes:jpeg,png,jpg,gif|max:2048'],
        ]);

        // Delete existing profile image if it's not the default 'no_image'
        if ($user->profile_image && $user->profile_image !== 'no_image') {
            Storage::delete(str_replace('/storage/app/public/', 'public/', $user->profile_image));
        }

        // Handle the new profile image
        $profileImageName = uniqid('profile_image_') . '.' . $request->file('profile_image')->getClientOriginalExtension();
        $request->file('profile_image')->storeAs('public/profile_images', $profileImageName);

        // Update the user's profile image
        $user->update([
            'profile_image' => asset('storage/profile_images/' . $profileImageName),
        ]);
        

        return response()->json(['message' => 'Profile image updated successfully', 'user' => $user], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

*/

    public function viewPeople(Request $request)
    {
        try {
            $currentUser = Auth::user();

         
            $users = User::where('id', '!=', $currentUser->id)->where('role', 0)->get();

            return response()->json(['users' => $users], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showOtherProfile(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                return response()->json(['user' => $user], 200);
            } else {
                return response()->json(['user' => null], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

  
}
