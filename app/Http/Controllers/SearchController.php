<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
 

    public function search(Request $request)
    {
        try {
            $price = $request->input('price');
            $location = $request->input('location');
            $type = $request->input('type');
            $size = $request->input('size');
            $rooms = $request->input('rooms');
    
            $results = Post::where('price', 'like', '%' . $price . '%')
                ->where('location', 'like', '%' . $location . '%')
                ->where('type', 'like', '%' . $type . '%')
                ->where('size', 'like', '%' . $size . '%')
                ->where('rooms', 'like', '%' . $rooms . '%')
                ->get();
    
            return response()->json(['results' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

}
