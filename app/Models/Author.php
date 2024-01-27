<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'about',
        'arr_genres',
        'user_id'
    ];

    protected $casts = [
        'arr_genres' => 'array',
    ];

    public function genre()
    {
        return $this->hasOne(Genre::class, 'id', 'arr_genres');
    }
}
