<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkFlairForPost extends Model
{
    use HasFactory;

    protected $fillable = ['flair_id', 'post_id'];

    public function flair(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LinkFlair::class, 'id', 'flair_id');
    }

    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class, 'id', 'post_id');
    }

}
