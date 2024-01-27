<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostUser extends Pivot
{
    protected $fillable = [
        'user_id',
        'post_id',
        'code_vote',
    ];
    protected $table = 'post_user';
}
