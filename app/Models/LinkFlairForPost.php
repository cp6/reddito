<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkFlairForPost extends Model
{
    use HasFactory;

    protected $fillable = ['flair_id', 'post_id'];
}
