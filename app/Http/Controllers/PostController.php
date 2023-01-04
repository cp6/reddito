<?php

namespace App\Http\Controllers;

use App\Models\Fetch;
use App\Models\Post;
use App\Models\Sub;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function update(Post $post): \Illuminate\Http\JsonResponse
    {
        $posts = Post::where('updated_at', '>=', Carbon::now()->subHours(1)->toDateTimeString())->take(50)->get();

        foreach ($posts as $post_a){
            Post::processPost($post_a);
        }

        return response()->json(['call' => 'update', 'post' => $post->id], 200)->header('Content-Type', 'application/json');
    }

    public function updateMany(int $amount = 30): \Illuminate\Http\JsonResponse
    {
        $posts = Post::where('updated_at', '>=', Carbon::now()->subHours(2)->toDateTimeString())
            ->where('created_at', '<=', Carbon::now()->subDay(1)->toDateTimeString())->take($amount)->get();
        //Posts that have NOT been updated in 2 hours and are NOT older than 1 day

        foreach ($posts as $post){
            dump($post);
            Post::processPost($post);
        }

        return response()->json(['call' => 'updateMany', 'amount' => $amount], 200)->header('Content-Type', 'application/json');
    }

    public function updateFromSubs(int $subs_amount = 30, int $api_amount = 99): \Illuminate\Http\JsonResponse
    {
        $subs = Post::where('status', 1)->where('updated_at', '>=', Carbon::now()->subHours(1)->toDateTimeString())
            ->take($subs_amount)->get()->unique('sub_id');

        $subs_array = [];

        foreach ($subs as $sub){
            $fetch = new Fetch("https://www.reddit.com/r/{$sub->sub->name}/new.json?sort=new&limit={$api_amount}");
            Sub::processSubPosts($fetch->getData());
            $subs_array[] = $sub->sub->name;
        }

        return response()->json(['call' => 'updateFromSubs', 'subs_amount' => $subs_amount, 'api_amount' => $api_amount, 'subs' => $subs_array], 200)
            ->header('Content-Type', 'application/json');
    }

}
