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

    public static function do(string $post_id, string $author_id, array $val): FlairsForPost
    {
        return self::updateOrCreate(['flair_id' => $val['data']['author_flair_template_id'], 'post_id' => $post_id], ['author_id' => $author_id]);
    }

}
