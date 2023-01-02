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

    public function processQueue(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PostProcessQueue::class, 'id', 'id');
    }

    public function sub(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Sub::class, 'id', 'sub_id');
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Author::class, 'id', 'author_id');
    }

    public function domain(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Domain::class, 'id', 'domain_id');
    }

    public function title(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Title::class, 'id', 'id');
    }

    public function url(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Url::class, 'id', 'id');
    }

}
