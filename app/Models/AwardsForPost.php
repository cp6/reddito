<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardsForPost extends Model
{
    use HasFactory;

    protected $fillable = ['award_id', 'post_id', 'count'];
}
