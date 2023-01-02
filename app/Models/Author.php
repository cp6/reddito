<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';

    public $incrementing = false;

    protected $fillable = ['id', 'username', 'posts', 'score', 'comments', 'subs', 'posts_18_plus', 'total_score', 'comments_on_posts', 'subs_posted_to'];

}
