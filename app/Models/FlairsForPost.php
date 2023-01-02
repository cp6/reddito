<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlairsForPost extends Model
{
    use HasFactory;

    protected $fillable = ['flair_id', 'post_id', 'author_id'];

    public function flair(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Flair::class, 'id', 'flair_id');
    }

    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class, 'id', 'post_id');
    }

}
