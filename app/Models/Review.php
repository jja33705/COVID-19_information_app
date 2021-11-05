<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'place',
        'title',
        'contents',
        'viewCount'
    ];

    protected $with = ['hashtags'];

    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class);
    }
}
