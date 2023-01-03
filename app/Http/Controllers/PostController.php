<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function update(Post $post): \Illuminate\Http\JsonResponse
    {
        //Post::updatePost($post);
        $posts = Post::where('updated_at', '>=', Carbon::now()->subHours(1)->toDateTimeString())->take(50)->get();

        foreach ($posts as $post_a){
            Post::updatePost($post_a);
        }


        //Update the post
        //Call its json file

        return response()->json(['calls' => []], 200)->header('Content-Type', 'application/json');
    }

    public function updateFromSubs(int $amount = 30): \Illuminate\Http\JsonResponse
    {
        $subs = Post::where('updated_at', '>=', Carbon::now()->subHours(1)->toDateTimeString())
            ->take($amount)->get()->unique('sub_id');

        foreach ($subs as $s){
            Post::updatePostsFromSub($s->sub);
        }

        return response()->json(['calls' => []], 200)->header('Content-Type', 'application/json');
    }

}
