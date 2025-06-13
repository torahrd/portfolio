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
        // ✅ 修正: ページネーション対応 + パフォーマンス最適化
        $posts = Post::with([
            'shop:id,name,address',        // 店舗情報（必要な列のみ）
            'user:id,name',                // ユーザー情報（必要な列のみ）
            'comments' => function ($query) {
                $query->with('user:id,name')  // コメントのユーザー情報
                    ->orderBy('created_at', 'desc')
                    ->limit(3);              // 最新3件のみ
            }
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(12); // ✅ get() → paginate(12) に変更

        return view('post.index', compact('posts'));
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
        // バリデーション追加
        $validated = $request->validate([
            'post.shop_id' => 'required|exists:shops,id',
            'post.visit_status' => 'required|boolean',
            'post.body' => 'nullable|string|max:1000',
            'post.budget' => 'nullable|integer|min:0',
            'post.menus' => 'nullable|string|max:500',
            'post.reference_url' => 'nullable|url|max:255',
            'post.folder_id' => 'nullable|exists:folders,id'
        ]);

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

        return redirect('/posts')->with('success', '投稿を作成しました');
    }

    public function show(Post $post)
    {
        // 関連データを効率的に取得
        $post->load([
            'user:id,name',
            'shop:id,name,address',
            'comments' => function ($query) {
                $query->with('user:id,name')
                    ->orderBy('created_at', 'desc');
            },
            'folders:id,name'
        ]);

        return view('post.show', compact('post'));
    }

    public function edit(Post $post, Shop $shop)
    {
        // 認可チェック
        $this->authorize('update', $post);

        $user = Auth::user();
        $folders = $user->folders;
        $shops = $shop->get();

        return view('post.edit', compact('folders', 'shops', 'post'));
    }

    public function update(Request $request, Post $post)
    {
        // 認可チェック
        $this->authorize('update', $post);

        // バリデーション
        $validated = $request->validate([
            'post.shop_id' => 'required|exists:shops,id',
            'post.visit_status' => 'required|boolean',
            'post.body' => 'nullable|string|max:1000',
            'post.budget' => 'nullable|integer|min:0',
            'post.menus' => 'nullable|string|max:500',
            'post.reference_url' => 'nullable|url|max:255'
        ]);

        $input_post = $request['post'];
        $post->fill($input_post)->save();

        return redirect('/posts/' . $post->id)->with('success', '投稿を更新しました');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect('/posts')->with('success', '投稿を削除しました');
    }
}
