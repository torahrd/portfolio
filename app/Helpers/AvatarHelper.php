<?php

declare(strict_types=1);

namespace App\Helpers;

if (! function_exists('App\Helpers\getInitial')) {
    /**
     * ユーザー名から頭文字を生成する
     *
     * @param  string|null  $name  ユーザー名
     * @return string 頭文字
     */
    function getInitial(?string $name): string
    {
        if (empty($name)) {
            return '';
        }

        // 日本語（マルチバイト文字）が含まれているかチェック
        if (preg_match('/[^\x01-\x7E]/', $name)) {
            // 最初のスペースまたはマルチバイト文字の区切りで分割
            $parts = preg_split('/(\s|　)/u', $name, -1, PREG_SPLIT_NO_EMPTY);
            $firstName = $parts[0] ?? '';

            return mb_substr($firstName, 0, 1);
        }

        // 英語（シングルバイト文字）の場合の処理
        $words = explode(' ', $name);
        $firstWord = $words[0] ?? '';

        return strtoupper(substr($firstWord, 0, 1));
    }
}
