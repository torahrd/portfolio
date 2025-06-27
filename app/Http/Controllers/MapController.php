<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
  public function index(Request $request)
  {
    // 中心座標やパラメータはBladeに渡す（今後拡張）
    return view('map.index');
  }
}
