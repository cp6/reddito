<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Award extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'title', 'desc', 'icon', 'icon_small', 'price', 'gives_reward'];

    public function awardsForPost(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AwardsForPost::class, 'award_id', 'id');
    }

    public static function do(array $award): Award
    {
        return self::updateOrCreate(['id' => $award['id']], [
            'title' => $award['name'],
            'desc' => $award['description'],
            'price' => $award['coin_price'],
            'icon' => $award['icon_url'],
            'icon_small' => $award['resized_icons'][3]['url'] ?? null
        ]);
    }

}
