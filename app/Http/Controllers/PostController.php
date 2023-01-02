<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function update(Post $post): \Illuminate\Http\JsonResponse
    {
        dump($post);
        //Update the post
        //Call its json file

        return response()->json(['calls' => []], 200)->header('Content-Type', 'application/json');
    }

}
