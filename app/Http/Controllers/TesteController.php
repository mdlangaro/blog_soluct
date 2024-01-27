<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TesteController extends Controller
{
    public function teste()
    {
        $posts = User::find(2);
        $pivot = $posts->pivot;
        dd($pivot);
    }
}
