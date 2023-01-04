<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardsForPost extends Model
{
    use HasFactory;

    protected $fillable = ['award_id', 'post_id', 'count'];

    public function award(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Award::class, 'id', 'award_id');
    }

    public function post(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

    public function do(string $post_id, array $award): AwardsForPost
    {
        return self::updateOrCreate(['post_id' => $post_id, 'award_id' => $award['id']], [
            'count' => $award['count']
        ]);
    }

}
