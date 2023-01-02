<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Sub;
use Illuminate\Http\Request;

class SubController extends Controller
{
    public function countComments(Sub $sub): Sub
    {
        $comments = Post::where('sub_id', $sub->id)->sum('comments');

        $sub->update(['comments_on_posts' => $comments]);

        return $sub;
    }

    public function countScore(Sub $sub): Sub
    {
        $score_total = Post::where('sub_id', $sub->id)->sum('score');

        $sub->update(['total_score' => $score_total]);

        return $sub;
    }

}
