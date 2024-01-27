<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'content',
    ];

    protected $table = 'genres';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
