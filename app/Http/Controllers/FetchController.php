<?php

namespace App\Http\Controllers;

use App\Models\Fetch;
use App\Models\Post;
use App\Models\Sub;

class FetchController extends Controller
{

    public function getNew(string $sub = 'all', int $amount = 99, int $loops = 5): \Illuminate\Http\JsonResponse
    {
        $fetch = new Fetch("https://www.reddit.com/r/{$sub}/new.json?sort=new&limit={$amount}");

        //Assuming this is getting called every minute from CRON
        //Draw out 1 minute as not to miss posts
        //If doing subreddit ONLY you can just call a couple of times throughout the minute

        $result_array = [];
        for ($i = 1; $i <= $loops; $i++) {//5 calls with a 4 second break after each one
            $result_array[] = Sub::processSubPosts($fetch->getData());
            sleep(4);
        }//If this takes more than 1 minute on your system do less than 5 loops

        return response()->json(['calls' => [$result_array]], 200)->header('Content-Type', 'application/json');

    }

    public function getHot(string $sub = 'all', int $amount = 99, int $loops = 5): \Illuminate\Http\JsonResponse
    {
        $fetch = new Fetch("https://www.reddit.com/r/{$sub}/hot.json?sort=hot&limit={$amount}");

        $result_array = [];
        for ($i = 1; $i <= $loops; $i++) {//5 calls with a 4 second break after each one
            $result_array[] = Sub::processSubPosts($fetch->getData());
            sleep(4);
        }//If this takes more than 1 minute on your system do less than 5 loops

        return response()->json(['calls' => [$result_array]], 200)->header('Content-Type', 'application/json');

    }

}
