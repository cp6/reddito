<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['id', 'title', 'cleaned_title', 'was_cleaned'];

    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class, 'id', 'id');
    }


}
