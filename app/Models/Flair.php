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

}
