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
        // フォルダIDを取得して除外
        $folderId = $input['folder_id'] ?? null;
        unset($input['folder_id']);
        $post->fill($input)->save();
        // フォルダの関連付け（単一フォルダ）
        if ($folderId) {
            // 自分のフォルダかチェック
            $userFolderIds = auth()->user()->folders->pluck('id')->toArray();
            if (in_array($folderId, $userFolderIds)) {
                $post->folders()->attach($folderId);
            }
        }
        return redirect('/posts');
    }

    public function show(Post $post)
    {
        return view('post.show', compact('post'));
    }

    public function edit(Post $post, Shop $shop)
    {
        $user = Auth::user();

        $folders = $user->folders;

        $shops = $shop->get();

        return view('post.edit', compact('folders', 'shops', 'post'));
    }

    public function update(Request $request, Post $post)
    {
        $input_post = $request['post'];
        $post->fill($input_post)->save();

        return redirect('/posts/' . $post->id);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();
        return redirect('/posts')->with('succes', '削除しました');
    }
}
