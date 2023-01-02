<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Post;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function countComments(Domain $domain): Domain
    {
        $comments = Post::where('domain_id', $domain->id)->sum('comments');

        $domain->update(['comments_on_posts' => $comments]);

        return $domain;
    }

    public function countScore(Domain $domain): Domain
    {
        $score_total = Post::where('domain_id', $domain->id)->sum('score');

        $domain->update(['total_score' => $score_total]);

        return $domain;
    }

    public function countUniqueSubs(Domain $domain): Domain
    {
        $unique_subs_total = Post::where('domain_id', $domain->id)->groupBy('sub_id')->get()->count();

        $domain->update(['subs_posted_to' => $unique_subs_total]);

        return $domain;
    }

}
