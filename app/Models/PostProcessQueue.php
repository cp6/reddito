<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostProcessQueue extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'increment_author_posts_count', 'increment_author_subs_count', 'increment_sub_posts_count', 'increment_domain_posts_count', 'increment_domain_unique_subs_count'];

}
