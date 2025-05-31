<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Folder;
use App\Models\Shop;

class PostController extends Controller
{
    public function index(Post $post)
    {
        return view('post.index')->with('posts', $post->get());
    }

    public function create(Shop $shop)
    {
        $user = Auth::user();

        $folders = $user->folders;

        $shops = $shop->get();

        return view('post.create', compact('folders', 'shops'));
    }

    public function store(Request $request, Post $post)
    {
        $input = $request['post'];
        $input['user_id'] = Auth::user()->id;
        $post->fill($input)->save();
        return redirect('/posts');
    }
}
