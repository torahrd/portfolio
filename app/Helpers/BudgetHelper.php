<?php

namespace App\Helpers;

class BudgetHelper
{
  /**
   * 予算の数値を表示用の文字列に変換
   * 
   * @param int|null $budget データベースの予算値
   * @return string 表示用文字列
   */
  public static function formatBudget($budget)
  {
    // 予算が設定されていない場合
    if (empty($budget)) {
      return '予算未設定';
    }

    // create.blade.phpのselect optionに対応した予算範囲
    $ranges = [
      1000 => '〜¥1,000',
      2000 => '¥1,000〜¥2,000',
      3000 => '¥2,000〜¥3,000',
      5000 => '¥3,000〜¥5,000',
      10000 => '¥5,000〜¥10,000',
      30000 => '¥10,000〜¥30,000',
      50000 => '¥30,000〜¥50,000',
      50001 => '¥50,000〜'
    ];

    // 該当する範囲があれば表示、なければ数値をそのまま表示
    return $ranges[$budget] ?? '¥' . number_format($budget);
  }
}
