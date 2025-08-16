<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class LandingPageController extends Controller
{
    public function index()
    {
        // 24節気データを読み込み
        $seasonTermsPath = base_path('data/season_terms.json');
        $seasonTerms = File::exists($seasonTermsPath)
            ? json_decode(File::get($seasonTermsPath), true)
            : [];

        // 現在の節気を判定（年跨ぎ対応）
        $currentDate = now();
        $currentTerm = null;

        foreach ($seasonTerms as $index => $term) {
            $start = \Carbon\Carbon::parse($term['start']);
            $end = \Carbon\Carbon::parse($term['end']);

            // 年跨ぎの場合（start > end）
            if ($start->gt($end)) {
                if ($currentDate->gte($start) || $currentDate->lte($end)) {
                    $currentTerm = $index;
                    break;
                }
            } else {
                // 通常の範囲チェック
                if ($currentDate->between($start, $end)) {
                    $currentTerm = $index;
                    break;
                }
            }
        }

        // 現在の節気がない場合は立春を使用
        if ($currentTerm === null) {
            $currentTerm = 0;
        }

        return view('landing', compact('seasonTerms', 'currentTerm'));
    }
}
