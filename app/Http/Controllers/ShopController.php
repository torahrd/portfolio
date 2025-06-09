<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Http\Requests\ShopSearchRequest;
use App\Http\Requests\ShopStoreRequest;
use Illuminate\Support\Facades\Cache;

class ShopController extends Controller
{
    /**
     * ★ 新規追加: 店舗詳細画面を表示 ★
     */
    public function show(Shop $shop)
    {
        // 必要なデータを効率的に読み込み
        $shop->load([
            'business_hours', // 営業時間
            'favorited_by_users:id,name', // お気に入りユーザー（最小限のデータ）
            'posts' => function ($query) {
                // 最近の投稿5件を投稿者情報付きで取得
                $query->with('user:id,name')
                    ->orderBy('created_at', 'desc')
                    ->limit(5);
            }
        ]);

        // 現在のユーザーがお気に入りしているかチェック
        $isFavorited = auth()->check() ? $shop->isFavoritedBy(auth()->id()) : false;

        // 曜日名の配列（日本語）
        $dayNames = ['日', '月', '火', '水', '木', '金', '土'];

        return view('shops.show', compact('shop', 'isFavorited', 'dayNames'));
    }

    /**
     * 店舗検索（AJAX専用）- 既存機能
     */
    public function search(ShopSearchRequest $request)
    {
        // 1. AJAXリクエストかチェック
        if (!$request->ajax()) {
            return response()->json(['error' => 'AJAX request required'], 400);
        }

        // 2. バリデーション済みデータを取得
        $query = $request->validated()['query'];
        $cacheKey = 'shop_search_' . md5($query);

        // 3. データベースから部分一致で検索
        $shops = Cache::remember($cacheKey, 300, function () use ($query) {
            return Shop::where('name', 'LIKE', "%{$query}%")
                ->select('id', 'name', 'address')
                ->orderBy('name')
                ->limit(10)
                ->get();
        });

        // 4. 結果を返す
        return response()->json([
            'shops' => $shops,
            'has_results' => $shops->count() > 0
        ]);
    }

    /**
     * 新しい店舗を作成（AJAX専用）- 既存機能
     */
    public function store(ShopStoreRequest $request)
    {
        // 1. AJAXリクエストかチェック
        if (!$request->ajax()) {
            return response()->json(['error' => 'AJAX request required'], 400);
        }

        // 2. 新しい店舗を作成
        $shop = Shop::create($request->validated());

        // 3. 作成結果を返す
        return response()->json([
            'success' => true,
            'shop' => $shop
        ]);
    }
}
