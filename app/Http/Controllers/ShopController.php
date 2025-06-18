<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Http\Requests\ShopSearchRequest;
use App\Http\Requests\ShopStoreRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

        // ★デバッグ情報を追加★
        Log::info('Shop data loaded:', [
            'shop_id' => $shop->id,
            'shop_name' => $shop->name,
            'business_hours_count' => $shop->business_hours->count(),
            'posts_count' => $shop->posts->count(),
            'recent_posts_count' => $shop->recent_posts->count(),
        ]);

        // 現在のユーザーがお気に入りしているかチェック
        $isFavorited = auth()->check() ? $shop->isFavoritedBy(auth()->id()) : false;

        // 曜日名の配列（日本語）
        $dayNames = ['日', '月', '火', '水', '木', '金', '土'];

        return view('shops.show', compact('shop', 'isFavorited', 'dayNames'));
    }

    /**
     * 店舗検索（AJAX対応）
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100'
        ]);

        try {
            $query = $request->get('q');

            $shops = Shop::where('name', 'LIKE', "%{$query}%")
                ->orWhere('address', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get()
                ->map(function ($shop) {
                    return [
                        'id' => $shop->id,
                        'name' => $shop->name,
                        'address' => $shop->address,
                    ];
                });

            return response()->json([
                'success' => true,
                'shops' => $shops
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '店舗検索に失敗しました',
                'shops' => []
            ], 500);
        }
    }


    /**
     * 新規店舗作成（AJAX対応）
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ]);

        try {
            $shop = Shop::create([
                'name' => $request->name,
                'address' => $request->address,
                'created_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => '店舗が作成されました',
                'shop' => [
                    'id' => $shop->id,
                    'name' => $shop->name,
                    'address' => $shop->address,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '店舗の作成に失敗しました'
            ], 500);
        }
    }

    /**
     * 最近投稿された店舗を取得（投稿作成画面用）
     */
    public function recent(Request $request)
    {
        try {
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

            return response()->json([
                'success' => true,
                'shops' => $recentShops
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '最近の店舗取得に失敗しました',
                'shops' => []
            ], 500);
        }
    }
}
