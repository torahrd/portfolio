<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Post;

class SearchController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->input('q', '');
    $shop = null;
    $posts = collect();
    if ($query) {
      $shop = Shop::where('name', 'like', "%{$query}%")->first();
      if ($shop) {
        $posts = Post::where('shop_id', $shop->id)->latest()->get();
      }
    }
    return view('search.index', [
      'query' => $query,
      'shop' => $shop,
      'posts' => $posts,
    ]);
  }
}
