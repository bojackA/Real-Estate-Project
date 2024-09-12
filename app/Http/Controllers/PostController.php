<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use App\Models\SavedListing;
use App\Models\Ownership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    
    public function showApprovedPosts()
    {
        $approvedPosts = Post::where('permission', true)->where('user_id', '=', auth()->id())->get();

        return response()->json(['approvedPosts' => $approvedPosts], 200);
    }

    public function showOtherPosts()
    {
        $approvedPosts = Post::where('permission', true)
            ->where('user_id', '!=', auth()->id())
            ->get();

        return response()->json(['Listings' => $approvedPosts], 200);
    }
    

    public function saveToBookmarks($postId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            
            $post = Post::find($postId);

            if (!$post) {
                return response()->json(['error' => 'Post not found.'], 404);
            }

          
            if ($post->permission != 1) {
                return response()->json(['error' => 'Cannot save post to bookmarks.'], 403);
            }

            
            $existingBookmark = SavedListing::where('user_id', $user->id)
                ->where('post_id', $postId)
                ->first();

            if ($existingBookmark) {
                return response()->json(['message' => 'Post already in bookmarks.'], 200);
            }

          
            SavedListing::create([
                
                'post_id' => $postId,
                'user_id' => $user->id,
            ]);

            return response()->json(['message' => 'Post saved to bookmarks successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function viewSavedPosts()
{
    try {
        $user = Auth::user();
        $allPosts = Post::all();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $savedPosts = SavedListing::where('user_id', $user->id)->with('post')->get();

        return response()->json(['savedPosts' => $savedPosts], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



public function createPost(Request $request)
{
    try {
        /** @var \App\Models\User $user **/
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'title' => ['required','regex:/^[\p{L} ]+$/u'],
            'body' => ['required','regex:/^[\p{L} ]+$/u'],
            
            
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'image2.*' =>'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3.*' =>'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image4.*' =>'image|mimes:jpeg,png,jpg,gif|max:2048',
        
            'id_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'ownership_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

            

        ]);
        

        $title = strip_tags($request->input('title'));
        $body = strip_tags($request->input('body'));

        $userID = $user->id;
        


        $post = Post::create([
            'title' => $title,
            'body' => $body,
            'user_id' => $userID,
            
            'bathrooms' => $request->input('bathrooms'),
            'rooms' => $request->input('rooms'),
            'location' => $request->input('location'),
            'address' => $request->input('address'),
            'size' => $request->input('size'),
            'type' => $request->input('type'),
            'price' => $request->input('price'),
            
            'name' => $user->name,
            'phone' => $user->phone,
        
        ]); 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid('post_image_') . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/post_images', $imageName);
            $imagePath = asset('storage/app/public/post_images/' . $imageName);


         if ($request->hasFile('image2')) {
            $image2 = $request->file('image2');
            $imageName2 = uniqid('post_image2_') . '.' . $image2->getClientOriginalExtension();
            $image2->storeAs('public/post_images', $imageName2);
            $imagePath2 = asset('/storage/app/public/post_images/' . $imageName2);
        
         if ($request->hasFile('image3')) {
            $image3 = $request->file('image3');
            $imageName3 = uniqid('post_image3_') . '.' . $image3->getClientOriginalExtension();
            
            $imagePath3 = asset('/storage/app/public/post_images/' . $imageName3);
        
          $imageName4 = 'post_image4_65abdbaa3ee0b.jpg';
          $imagePath4 = "post_images/$imageName4";
          $imageUrl4 = Storage::url($imagePath);

            
            
        
            $post->images()->create([
                'post_id' => $post->id,
                'image' => json_encode(asset($imagePath)),
                'image2' => $imagePath2,
                'image3' => $imagePath3,
                'image4' => $imagePath4,

                
            ]);
        

         }
        }
    }

                if ($request->hasFile('id_image')) {
                    $idImageName = uniqid('id_image_') . '.' . $request->file('id_image')->getClientOriginalExtension();
                    $var = date_create();
                    $time = date_format($var, 'YmdHis');
                    $idImageName = $time . '-' . $idImageName;
                    $uploade_path = 'public/id_images/'; // Adjust the upload path as needed
                    $idImageUrl = $uploade_path . $idImageName;
                    $request->file('id_image')->storeAs($uploade_path, $idImageName);
                    $idimagePath = '/storage/app/' . $idImageUrl;







                    
                
     if ($request->hasFile('ownership_image')) {
        $ownimage = $request->file('ownership_image');
        $ownimageName = uniqid('ownership_image_') . '.' . $ownimage->getClientOriginalExtension();
        $ownimage->storeAs('public/ownership_images', $ownimageName);
        $ownimagePath = asset('/storage/app/public/ownership_images/' . $ownimageName);
    
    
       
        $post->ownimages()->create([
            'post_id' => $post->id,
            'id_image' => $idimagePath,
            'ownership_image' => $ownimagePath,
           

            
        ]);
    }

     }
     $post->load('images', 'ownimages');

    return response()->json(['image_url '=> $imageUrl4 ,'message' => 'Post Pending Admin Approval', 'post' => $post], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}


/*

    public function createPost(Request $request)
    {
       
        /*$user = Auth::user();
        
        $request->validate([
            'title' => 'required|min:5',
            'body' => 'required|min:10',
            'images' => 'required|array|max:20',
        ]);
        

        $title = $request->input('title');
        $body = $request->input('body');

        $post = Post::create([
            'title' => $title,
            'body' => $body,
            'user_id' => auth()->user()->id,
            'bathrooms' => $request->input('bathrooms'),
            'rooms' => $request->input('rooms'),
            'location' => $request->input('location'),
            'address' => $request->input('address'),
            'size' => $request->input('size'),
            'type' => $request->input('type'),
            'price' => $request->input('price'),
            'id_image' => $request->input('id_image'),
            'ownership_image' => $request->input('ownership_image'),
            'name' => $user->name,
            'phone' => $user->phone,
        ]);

        $images = [];
        foreach ($request->file('images') as $image) {
            $filename = $image->store('public/post_images');
            $imageUrl = 'http://localhost:8000/storage/app/public/post_images/' . $filename;

            $image = Image::create([
                'image' => $imageUrl,
                'post_id' => $post->id,
                'user_id' => auth()->user()->id,
            ]);

            $images[] = $image;
        }

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
            'images' => $images,
        ]);
    }
}

*/
/*
public function uploadImages(Request $request)
{
   
    $request->validate([
        'images' => 'required|array',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
    ]);

   
    $user = Auth::user();

    // Loop through each uploaded image
    foreach ($request->file('images') as $image) {
        // Store the image in your storage (you may want to customize this based on your storage configuration)
        $path = $image->store('images', '/public/post_images');

        // Create a new image record in the database
        Image::create([
            'user_id' => $user->id,
            'image_path' => $path,
        ]);
    }

    return response()->json(['message' => 'Images uploaded successfully']);
}
}

    /*
    public function store(Request $request)
    {
        $this->validate($request, [
            'images.*' => 'image'
        ]);
    
        $user_id = Auth::id(); // Get the user's ID
    
        $files = [];
    
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $file) {
                $name = time() . rand(1, 50) . '.' . $file->extension();
                $file->storeAs('public/post_images', $name);  // Use storeAs to store in the specified folder
    
                // Save the image details in the images table
                Image::create([
                    'user_id' => $user_id,
                    'post_id' => null, // You might not have the post ID here if it's a separate image upload
                    'image_path' => '/storage/app/public/post_images/' . $name,
                ]);
    
                $files[] = $name;
            }
        }
    
        return back()->with('success', 'Images are successfully uploaded');
    }
    
}    
*/