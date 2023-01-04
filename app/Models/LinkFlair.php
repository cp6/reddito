<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LinkFlair extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'type', 'text', 'css_class', 'text_color', 'richtext_1', 'richtext_2', 'richtext_3'];

    public function flairsForPost(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LinkFlairForPost::class, 'flair_id', 'id');
    }

    public static function do(array $val): LinkFlair
    {
        return self::updateOrCreate(['id' => $val['link_flair_template_id']], [
            'type' => $val['link_flair_type'], 'text' => $val['link_flair_text'],
            'text_color' => (!empty($val['link_flair_text_color'])) ? $val['link_flair_text_color'] : null,
            'css_class' => (!empty($val['link_flair_css_class'])) ? $val['link_flair_css_class'] : null,
            'richtext_1' => $val['link_flair_richtext']['e'] ?? null, 'richtext_2' => $val['link_flair_richtext']['t'] ?? null
        ]);
    }

}
