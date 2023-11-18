<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Post\StorePostRequest;
use App\Http\Requests\API\Post\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        $posts = Post::all();

        return response()->json([
            $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $request->validated($request->all());

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json([
            $post
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       try
       {
        $post = Post::findOrFail($id);

        return response()->json([
            $post
        ]);
       }
       catch(ModelNotFoundException $ex)
       {
        return response()->json([
            'Post Not Found'
        ],404);
       }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
       try
       {
        $post = Post::findOrFail($id);

        $request->validated($request->all());

        $post->update([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json([$post]);
       }
       catch(ModelNotFoundException $ex)
       {
        return response()->json([
            'Post Not Found'
        ],404);
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try
        {
            $post = Post::findOrFail($id);

            $post->delete();
    
            return response()->json([],204);
        }
        catch(ModelNotFoundException $ex)
        {
            return response()->json([
                'Post Not Found'
            ],404);
        }
    }
}
