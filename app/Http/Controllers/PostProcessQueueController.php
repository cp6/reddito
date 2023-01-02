<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\PostProcessQueue;
use Illuminate\Http\Request;

class PostProcessQueueController extends Controller
{
    public function doAuthorCounts(int $amount = 20): void
    {
        $posts = PostProcessQueue::where('do_author_counts', 0)->with(['post', 'post.domain', 'post.author'])->take($amount)->get();

        foreach ($posts as $post) {

            $author = $post->post->author;

            $author->increment('posts');

            if ($post->post->over_18) {
                $author->increment('posts_18_plus');
            }

            $post->update(['do_author_counts' => 1]);
        }

    }

    public function doSubCounts(int $amount = 20): void
    {
        $posts = PostProcessQueue::where('do_sub_count', 0)->with(['post', 'post.sub'])->take($amount)->get();

        foreach ($posts as $post) {

            $sub = $post->post->sub;

            $sub->increment('posts');

            if ($post->post->over_18) {
                $sub->increment('posts_18_plus');
            }

            $post->update(['do_sub_count' => 1]);
        }

    }

    public function doDomainCounts(int $amount = 20): void
    {
        $posts = PostProcessQueue::where('do_domain_count', 0)->with(['post', 'post.domain'])->take($amount)->get();

        foreach ($posts as $post) {

            $domain = $post->post->domain;

            $domain->increment('amount');

            if ($post->post->over_18) {
                $domain->increment('amount_18_plus');
            }

            $post->update(['do_domain_count' => 1]);
        }

    }


}
