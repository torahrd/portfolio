<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Folder;
use App\Models\Shop;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PostController extends Controller
{
    public function index(Post $post)
    {
        // Eager Loadingで最適化
        $posts = Post::with([
            'shop:id,name,address',        // 店舗情報（必要な列のみ）
            'user:id,name,avatar,is_private',                // ユーザー情報（必要な列のみ）
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // 最近投稿した店舗を取得
            $recentShops = auth()->user()->posts()
                ->with('shop')
                ->latest()
                ->take(5)
                ->get()
                ->pluck('shop')
                ->filter() // nullを除外
                ->unique('id')
                ->values()
                ->map(function ($shop) {
                    return [
                        'id' => $shop->id,
                        'name' => $shop->name,
                        'address' => $shop->address,
                    ];
                });

            // ★修正: posts.create → post.create に変更
            return view('post.create', compact('recentShops'));
        } catch (\Exception $e) {
            // エラーが発生した場合は空の配列を渡す
            $recentShops = collect([]);
            return view('post.create', compact('recentShops'));
        }
    }

    public function store(Request $request, Post $post)
    {
        // 画像バリデーション追加
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        $input = $request['post'];
        $input['user_id'] = Auth::user()->id;

        // Cloudinary画像アップロード処理
        if ($request->hasFile('image')) {
            $image_url = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $input['image_url'] = $image_url;
        }

        // フォルダIDを取得して除外
        $folderId = $input['folder_id'] ?? null;
        unset($input['folder_id']);

        $post->fill($input)->save();

        // フォルダの関連付け（単一フォルダ）
        if ($folderId) {
            $userFolderIds = auth()->user()->folders->pluck('id')->toArray();
            if (in_array($folderId, $userFolderIds)) {
                $post->folders()->attach($folderId);
            }
        }

        return redirect()->route('posts.show', $post->id);
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
        return redirect('/posts')->with('success', '削除しました');
    }
}
