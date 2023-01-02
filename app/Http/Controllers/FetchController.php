<?php

namespace App\Http\Controllers;

use App\Models\Fetch;

class FetchController extends Controller
{

    public function get(): \Illuminate\Http\JsonResponse
    {
        $fetch = new Fetch("https://www.reddit.com/r/all/hot.json?t=day&limit=50");
        //$fetch = new Fetch('https://www.reddit.com/r/all/new.json?sort=new&limit=50');
        $data = $fetch->getData();
        return $fetch->processData($data);
    }

}
