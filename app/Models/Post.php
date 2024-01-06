<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content',
        'like',
        'category_id',
        'genre_id',
        'user_id',
        'viewcount'
    ];

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function genres()
    {
        return $this->hasMany(Genre::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}