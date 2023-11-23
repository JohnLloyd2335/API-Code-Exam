<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Post\StorePostRequest;
use App\Http\Requests\API\Post\UpdatePostRequest;
use App\Http\Response\HttpResponse;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostController extends Controller
{

    use HttpResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $limit = $request->limit ?? 10;

        $offset = ($page - 1) * $limit;

        $data = Post::offset($offset)->limit($limit)->get()->toArray();
        $total = Post::count();

        if (empty($data)) {
            return $this->successResponse(isSuccess: true, message: "No Post Found", data: [], statusCode: 200);
        }

        return $this->successPaginatedResponse(
            isSuccess: true,
            message: "",
            data: $data,
            totalRecords: $total,
            pageNumber: $page,
            perPage: $limit,
            offset: $offset,
            statusCode: 200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return $this->successResponse(true, "Post Created", $post->toArray(), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Post::findOrFail($id);
            return $this->successResponse(true, "", $post->toArray(), 200);
        } catch (ModelNotFoundException $ex) {
            return $this->failedResponse(false, "No Post Found", 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        try {
            $post = Post::findOrFail($id);

            $request->validated($request->all());

            $post->update([
                'title' => $request->title,
                'content' => $request->content
            ]);

            return $this->successResponse(true,"Post Updated Successfully", $post->toArray(), 200);
        } catch (ModelNotFoundException $ex) {
            return $this->failedResponse(false, "No Post Found", 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::findOrFail($id);

            $post->delete();

            return $this->successResponse(true,"Post Deleted Successfully", [] , 200);
        } catch (ModelNotFoundException $ex) {
            return $this->failedResponse(false, "No Post Found", 404);
        }
    }
}
