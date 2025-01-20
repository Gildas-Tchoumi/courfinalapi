<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        // get all posts
        $post = Post::all();
        return response()->json([
            'message' => 'vos post',
            'data' => $post
        ]);
    }

    public function store(Request $request)
    {
        try {
            // validate request
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'error',
                    'data' => $validator->errors()
                ], 400);
            }
            //Enregistrement de l'image dans le dossier storage/app/public
            $image = null;
            if($request->hasFile('image')) {
                $image = $request->file('image')->store('public/posts');
            }
            
            // create new post
            $post = Post::create([
                'title' => $request->title,
                'image' => $image,
                'description' => $request->description,
            ], 200);
            return response()->json([
                'message' => 'post create successfully',
                'data' => $post 
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error',
                'data' => $e->getMessage()
            ]);
        }
    }
}
