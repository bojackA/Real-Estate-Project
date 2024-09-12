<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        try {
            $incomingFields = $request->validate([
                'loginname' => 'required',
                'loginpass' => 'required',
            ]);

            if (Auth::attempt(['name' => $incomingFields['loginname'], 'password' => $incomingFields['loginpass']])) {
                $user = User::where('name',$incomingFields['loginname'])->first();
         
                $accessToken = $user->createToken('myapptoken')->plainTextToken;
    
             
                session(['user_id' => $user->id, 'access_token' => $accessToken]);

                if ($user->role == 0) {
                    
                    auth()->logout();
                    return response()->json(['error' => 'Unauthorized login for role 0'], 403);
                }

                
                return response()->json(['message' => 'Login successful','access_token' => $accessToken], 200);
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
    
    
    public function accept(Request $request, $id)
    {
        try {
            
            $post = Post::find($id);
    
            
            if (!$post) {
                return response()->json(['error' => 'Post not found.'], 404);
            }
    
            
            if (auth()->user()->role != 1) {
                return response()->json(['error' => 'Permission denied.'], 403);
            }
    
           
            $post->update(['permission' => 1]);
    
            return response()->json(['message' => 'Post accepted successfully.'], 200);
        } catch (\Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function destroyPost($id)
    {
        
        $post = Post::find($id);
    
       
        if (!$post) {
            return redirect('/new')->with('error', 'Post not found');
        }
    
        if (auth()->user()->role != 1) {
            return redirect('/new')->with('error', 'Permission denied');
        }
    
        
        $post->delete();
    
        return redirect('/new')->with('success', 'Post deleted successfully');
    }


    //delete  user from table
    
    public function destroy(Request $request, $id)
    {
        try {
    
            $user = User::find($id);
    
            
            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }
    
            
            if (auth()->user()->role != 1) {
                return response()->json(['error' => 'Permission denied.'], 403);
            }
    
        
            $user->delete();
    
            return response()->json(['message' => 'User deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function viewUsers()
    {
        try {
            $users = User::all();

            return response()->json(['users' => $users], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    public function showAllPosts()
    {
        try {
            
            $allPosts = Post::all();

            
            foreach ($allPosts as $post) {
                if (is_array($post->images)) {
                   
                    $post->images = collect($post->images);
                }
            }

            
            return response()->json(['allPosts' => $allPosts], 200);
        } catch (\Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function deletePost(Request $request, $id)
    {
        try {
            
            $post = Post::find($id);
    
            
            if (!$post) {
                return response()->json(['error' => 'Post not found.'], 404);
            }
    
          
            if (auth()->user()->role == 1) {
                
                $post->delete();
                return response()->json(['message' => 'Post deleted successfully.'], 200);
            } elseif ($post->user_id == auth()->id()) {
            
                $post->delete();
                return response()->json(['message' => 'Post deleted successfully.'], 200);
            } else {
               
                return response()->json(['error' => 'You are not authorized to delete this post.'], 403);
            }
        } catch (\Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
