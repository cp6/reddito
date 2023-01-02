<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'author_id', 'sub_id', 'domain_id', 'status', 'is_self', 'over_18', 'locked', 'has_awards', 'score', 'comments', 'awards', 'cross_posts', 'upvote_ratio', 'created_at', 'updated_at'];
}
