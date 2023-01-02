<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
