{{-- OGPタグコンポーネント --}}
@props([
    'title' => 'TasteRetreat - 自分だけの名店リストを作る',
    'description' => '行きたい店、また行きたい店をひとつに記録。24季節に重ねたリストで、あなたの特別な食体験を整理。',
    'image' => asset('images/ogp-main.png'),
    'url' => url()->current(),
    'type' => 'website',
])

{{-- 基本のOGPタグ --}}
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $title }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="TasteRetreat">
<meta property="og:locale" content="ja_JP">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

{{-- 追加のメタ情報 --}}
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $url }}">