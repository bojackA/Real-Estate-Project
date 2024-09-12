<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    

   
public function addToCart(Request $request)
{
    try {
        $propertyId = $request->input('id');

        if (!$propertyId) {
            return response()->json(['error' => 'Property ID is not required.'], 400);
        }

        $post = Post::find($propertyId);

        if (!$post) {
            return response()->json(['error' => 'Property not found.'], 404);
        }

        Property::create([
            'title' => $post->title,
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            
        ]);

        return response()->json(['message' => 'Post added to cart successfully.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    
}
