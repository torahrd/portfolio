<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Shop;

class SearchSuggestionController extends Controller
{
  /**
   * 店舗名サジェストAPI
   */
  public function shopNameSuggestions(Request $request): JsonResponse
  {
    $query = $request->input('q', '');
    if (mb_strlen($query) < 2) {
      return response()->json(['suggestions' => []]);
    }
    $shops = Shop::where('name', 'like', "%{$query}%")
      ->limit(5)
      ->get(['id', 'name']);
    $suggestions = $shops->map(function ($shop) {
      return [
        'title' => $shop->name,
        'url' => route('search', ['q' => $shop->name]),
        'category' => '店舗',
      ];
    });
    return response()->json(['suggestions' => $suggestions]);
  }
}
