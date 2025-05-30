<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $posts = $user->posts()->with('shop')->get();

        return view('user.index')->with('posts', $posts);
    }
}
