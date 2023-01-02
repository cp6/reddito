<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function countComments(Author $author): Author
    {
        $comments = Post::where('author_id', $author->id)->sum('comments');

        $author->update(['comments_on_posts' => $comments]);

        return $author;
    }

    public function countScore(Author $author): Author
    {
        $score_total = Post::where('author_id', $author->id)->sum('score');

        $author->update(['total_score' => $score_total]);

        return $author;
    }

    public function countUniqueSubs(Author $author): Author
    {
        $unique_subs_total = Post::where('author_id', $author->id)->groupBy('sub_id')->get()->count();

        $author->update(['subs_posted_to' => $unique_subs_total]);

        return $author;
    }

}
