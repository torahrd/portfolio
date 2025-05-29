<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\MyList;

class PostController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $lists = $user->myLists()->select('id', 'title')->get();

        return view('post.index')->with('lists', $lists);
    }
}
