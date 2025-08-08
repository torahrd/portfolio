<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $posts = $user->posts()->with('shop')->get();

        return view('user.index')->with('posts', $posts);
    }
}
