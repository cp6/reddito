<?php

namespace App\Http\Controllers;

use App\Models\Fetch;
use App\Models\Post;

class FetchController extends Controller
{

    public function get(): \Illuminate\Http\JsonResponse
    {
        //Track a certain subreddit (pics)
        //$fetch = new Fetch("https://www.reddit.com/r/pics/new.json?sort=new&limit=50");

        //Track ALL new Reddit posts
        $fetch = new Fetch('https://www.reddit.com/r/all/new.json?sort=new&limit=99');

        //Assuming this is getting called every minute from CRON
        //Draw out 1 minute as not to miss posts
        //If doing subreddit ONLY you can just call a couple of times throughout the minute

        $result_array = [];
        for ($i = 1; $i <= 9; $i++) {//9 calls with a 4 second break after each one
            $result_array[] = $fetch->processData($fetch->getData());
            sleep(4);
        }//If this takes more than 1 minute on your system do less than 9 loops

        return response()->json(['calls' => [$result_array]], 200)->header('Content-Type', 'application/json');

    }

}
