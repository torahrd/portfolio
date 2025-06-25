<?php

declare(strict_types=1);

namespace App\Services;

class TextNormalizationService
{
  /**
   * 店舗名を正規化
   */
  public function normalizeShopName(?string $name): string
  {
    if (is_null($name)) {
      $name = '';
    }
    // 1. ひらがな・カタカナ統一（カタカナに統一）
    $normalized = mb_convert_kana($name, 'KV');

    // 2. 全角・半角統一（半角に統一）
    $normalized = mb_convert_kana($normalized, 'RNASKV');

    // 3. 空白・記号の正規化
    $normalized = preg_replace('/\s+/', '', $normalized) ?? '';
    $normalized = preg_replace('/[()（）]/', '', $normalized) ?? '';
    $normalized = preg_replace('/[「」『』]/', '', $normalized) ?? '';
    $normalized = preg_replace('/[・･]/', '', $normalized) ?? '';

    // 4. 小文字に統一
    $normalized = strtolower($normalized);

    // 5. 特殊文字の除去
    $normalized = preg_replace('/[^\w\s]/u', '', $normalized) ?? '';

    return trim(is_string($normalized) ? $normalized : '');
  }

  /**
   * 住所を正規化
   */
  public function normalizeAddress(?string $address): string
  {
    if (is_null($address)) {
      $address = '';
    }
    // 1. ひらがな・カタカナ統一
    $normalized = mb_convert_kana($address, 'KV');

    // 2. 全角・半角統一
    $normalized = mb_convert_kana($normalized, 'RNASKV');

    // 3. 空白の正規化（単一スペースに統一）
    $normalized = preg_replace('/\s+/', ' ', $normalized) ?? '';

    // 4. 都道府県の正規化
    $normalized = $this->normalizePrefecture($normalized);

    // 5. 市区町村の正規化
    $normalized = $this->normalizeCity($normalized);

    // 6. 番地の正規化
    $normalized = $this->normalizeStreetNumber($normalized);

    return trim(is_string($normalized) ? $normalized : '');
  }

  /**
   * 都道府県の正規化
   */
  private function normalizePrefecture(string $address): string
  {
    $prefectureMap = [
      '北海道' => '北海道',
      '青森県' => '青森県',
      '岩手県' => '岩手県',
      '宮城県' => '宮城県',
      '秋田県' => '秋田県',
      '山形県' => '山形県',
      '福島県' => '福島県',
      '茨城県' => '茨城県',
      '栃木県' => '栃木県',
      '群馬県' => '群馬県',
      '埼玉県' => '埼玉県',
      '千葉県' => '千葉県',
      '東京都' => '東京都',
      '神奈川県' => '神奈川県',
      '新潟県' => '新潟県',
      '富山県' => '富山県',
      '石川県' => '石川県',
      '福井県' => '福井県',
      '山梨県' => '山梨県',
      '長野県' => '長野県',
      '岐阜県' => '岐阜県',
      '静岡県' => '静岡県',
      '愛知県' => '愛知県',
      '三重県' => '三重県',
      '滋賀県' => '滋賀県',
      '京都府' => '京都府',
      '大阪府' => '大阪府',
      '兵庫県' => '兵庫県',
      '奈良県' => '奈良県',
      '和歌山県' => '和歌山県',
      '鳥取県' => '鳥取県',
      '島根県' => '島根県',
      '岡山県' => '岡山県',
      '広島県' => '広島県',
      '山口県' => '山口県',
      '徳島県' => '徳島県',
      '香川県' => '香川県',
      '愛媛県' => '愛媛県',
      '高知県' => '高知県',
      '福岡県' => '福岡県',
      '佐賀県' => '佐賀県',
      '長崎県' => '長崎県',
      '熊本県' => '熊本県',
      '大分県' => '大分県',
      '宮崎県' => '宮崎県',
      '鹿児島県' => '鹿児島県',
      '沖縄県' => '沖縄県',
    ];

    foreach ($prefectureMap as $variant => $standard) {
      $address = str_replace($variant, $standard, $address);
    }

    return $address;
  }

  /**
   * 市区町村の正規化
   */
  private function normalizeCity(string $address): string
  {
    // 市区町村の表記ゆれを正規化
    $cityPatterns = [
      '/市$/u' => '市',
      '/区$/u' => '区',
      '/町$/u' => '町',
      '/村$/u' => '村',
    ];

    foreach ($cityPatterns as $pattern => $replacement) {
      $address = preg_replace($pattern, $replacement, $address);
    }

    return $address;
  }

  /**
   * 番地の正規化
   */
  private function normalizeStreetNumber(string $address): string
  {
    // 番地の表記ゆれを正規化
    $numberPatterns = [
      '/(\d+)丁目/u' => '$1丁目',
      '/(\d+)番地?/u' => '$1番',
      '/(\d+)号/u' => '$1号',
    ];

    foreach ($numberPatterns as $pattern => $replacement) {
      $address = preg_replace($pattern, $replacement, $address);
    }

    return $address;
  }

  /**
   * 類似度を計算（0.0-1.0）
   */
  public function calculateSimilarity(string $text1, string $text2): float
  {
    $normalized1 = $this->normalizeShopName($text1);
    $normalized2 = $this->normalizeShopName($text2);

    // レーベンシュタイン距離を使用
    $distance = levenshtein($normalized1, $normalized2);
    $maxLength = max(strlen($normalized1), strlen($normalized2));

    if ($maxLength === 0) {
      return 1.0;
    }

    return 1.0 - ($distance / $maxLength);
  }

  /**
   * 重複チェック（類似度が閾値を超える場合）
   */
  public function isDuplicate(string $text1, string $text2, float $threshold = 0.8): bool
  {
    return $this->calculateSimilarity($text1, $text2) >= $threshold;
  }

  /**
   * 住所の類似度を計算
   */
  public function calculateAddressSimilarity(string $address1, string $address2): float
  {
    $normalized1 = $this->normalizeAddress($address1);
    $normalized2 = $this->normalizeAddress($address2);

    // 住所の場合はより厳密に比較
    $distance = levenshtein($normalized1, $normalized2);
    $maxLength = max(strlen($normalized1), strlen($normalized2));

    if ($maxLength === 0) {
      return 1.0;
    }

    return 1.0 - ($distance / $maxLength);
  }
}
