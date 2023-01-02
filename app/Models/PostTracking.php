<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTracking extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'status', 'locked', 'has_awards', 'score', 'comments', 'awards', 'cross_posts', 'upvote_ratio', 'minutes_since_posted', 'created_at'];

}
