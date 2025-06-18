<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Folder;
use App\Models\Shop;
use App\Models\User;

class PostController extends Controller
{
    public function index(Post $post)
    {
        // ❌ 修正前（N+1問題が発生）
        // return view('post.index')->with('posts', $post->get());

        // ✅ 修正後（Eager Loadingで最適化）
        $posts = Post::with([
            'shop:id,name,address',        // 店舗情報（必要な列のみ）
            'user:id,name',                // ユーザー情報（必要な列のみ）
            'comments' => function ($query) {
                $query->with('user:id,name')  // コメントのユーザー情報
                    ->orderBy('created_at', 'desc')
                    ->limit(5);              // 最新5件のみ
            }
        ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('post.index', compact('posts'));
    }

    public function create()
    {
        $recentShops = auth()->user()->posts()
            ->with('shop')
            ->latest()
            ->take(5)
            ->get()
            ->pluck('shop')
            ->unique('id')
            ->values();

        return view('post.create', compact('recentShops'));
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
