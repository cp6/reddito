<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flair extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'text', 'type', 'text_color', 'background_color', 'css_class', 'rich_text_1', 'rich_text_2', 'rich_text_3', 'rich_text_4'];

    public function flairsForPost(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FlairsForPost::class, 'flair_id', 'id');
    }

    public static function do(array $val): Flair
    {
        return self::updateOrCreate(['id' => $val['data']['author_flair_template_id']], [
            'type' => $val['data']['author_flair_type'], 'text' => $val['data']['author_flair_text'],
            'text_color' => (!empty($val['data']['author_flair_text_color'])) ? $val['data']['author_flair_text_color'] : null,
            'background_color' => $val['data']['author_flair_background_color'],
            'css_class' => (!empty($val['data']['author_flair_css_class'])) ? $val['data']['author_flair_css_class'] : null,
            'richtext_1' => $val['data']['author_flair_richtext'][0]['a'] ?? null, 'richtext_2' => $val['data']['author_flair_richtext'][0]['u'] ?? null,
            'richtext_3' => $val['data']['author_flair_richtext'][1]['e'] ?? null, 'richtext_4' => $val['data']['author_flair_richtext'][1]['t'] ?? null
        ]);
    }

}
