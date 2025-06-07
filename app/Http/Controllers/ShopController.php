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
     * 店舗検索（AJAX専用）
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
     * 新しい店舗を作成（AJAX専用）
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
