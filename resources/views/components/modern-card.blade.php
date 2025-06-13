@props([
'variant' => 'default',
'size' => 'default',
'interactive' => false,
'elevated' => false,
'glass' => false,
'neumorphism' => false,
'padding' => 'default',
'rounded' => 'default',
'shadow' => 'default',
'border' => 'default',
'hover' => true,
'animate' => true,
'href' => null,
'header' => null,
'footer' => null,
'icon' => null,
'title' => null,
'subtitle' => null,
'badge' => null,
'actions' => null
])

@php
// ベースクラスの定義
$baseClasses = 'transition-all duration-300 ease-out';

// バリアント別のクラス
$variantClasses = [
'default' => 'bg-white border-neutral-200',
'primary' => 'bg-gradient-to-br from-mocha-50 to-mocha-100 border-mocha-200',
'secondary' => 'bg-gradient-to-br from-sage-50 to-sage-100 border-sage-200',
'accent' => 'bg-gradient-to-br from-electric-50 to-electric-100 border-electric-200',
'warning' => 'bg-gradient-to-br from-warning-50 to-warning-100 border-warning-200',
'success' => 'bg-gradient-to-br from-success-50 to-success-100 border-success-200',
'error' => 'bg-gradient-to-br from-error-50 to-error-100 border-error-200',
'dark' => 'bg-gradient-to-br from-neutral-800 to-neutral-900 border-neutral-700 text-white',
'gradient' => 'bg-gradient-to-br from-mocha-500 to-sage-500 text-white border-transparent',
];

// サイズ別のクラス
$sizeClasses = [
'xs' => 'text-xs',
'sm' => 'text-sm',
'default' => 'text-base',
'lg' => 'text-lg',
'xl' => 'text-xl',
];

// パディング別のクラス
$paddingClasses = [
'none' => 'p-0',
'xs' => 'p-2',
'sm' => 'p-4',
'default' => 'p-6',
'lg' => 'p-8',
'xl' => 'p-10',
];

// 角丸別のクラス
$roundedClasses = [
'none' => 'rounded-none',
'sm' => 'rounded-lg',
'default' => 'rounded-2xl',
'lg' => 'rounded-3xl',
'xl' => 'rounded-4xl',
'full' => 'rounded-full',
];

// シャドウ別のクラス
$shadowClasses = [
'none' => 'shadow-none',
'sm' => 'shadow-sm',
'default' => 'shadow-md',
'lg' => 'shadow-lg',
'xl' => 'shadow-xl',
'glass' => 'shadow-glass',
'neumorphism' => 'shadow-neumorphism',
];

// ボーダー別のクラス
$borderClasses = [
'none' => 'border-0',
'default' => 'border',
'thick' => 'border-2',
'dashed' => 'border border-dashed',
'dotted' => 'border border-dotted',
];

// 特殊エフェクト
$effectClasses = '';
if ($glass) {
$effectClasses .= ' glass-card';
}
if ($neumorphism) {
$effectClasses .= ' neumorphism';
}
if ($elevated) {
$effectClasses .= ' shadow-2xl';
}

// インタラクティブ効果
$interactiveClasses = '';
if ($interactive || $href) {
$interactiveClasses .= ' cursor-pointer';
if ($hover) {
$interactiveClasses .= ' hover:shadow-lg hover:-translate-y-1';
if ($glass) {
$interactiveClasses .= ' hover:bg-white/20';
} elseif ($neumorphism) {
$interactiveClasses .= ' hover:shadow-neumorphism-inset';
} else {
$interactiveClasses .= ' hover:shadow-xl hover:border-mocha-300';
}
}
}

// アニメーション
$animationClasses = '';
if ($animate) {
$animationClasses .= ' animate-fade-in';
}

// 最終的なクラス文字列の構築
$classes = implode(' ', [
$baseClasses,
$variantClasses[$variant] ?? $variantClasses['default'],
$sizeClasses[$size] ?? $sizeClasses['default'],
$paddingClasses[$padding] ?? $paddingClasses['default'],
$roundedClasses[$rounded] ?? $roundedClasses['default'],
$shadowClasses[$shadow] ?? $shadowClasses['default'],
$borderClasses[$border] ?? $borderClasses['default'],
$effectClasses,
$interactiveClasses,
$animationClasses,
]);

// 要素のタイプを決定
$element = $href ? 'a' : 'div';
$elementProps = $href ? ['href' => $href] : [];
@endphp

<{{ $element }} {{ $attributes->merge(['class' => $classes]) }} @if($href) href="{{ $href }}" @endif>
  @if($badge)
  <!-- バッジ -->
  <div class="absolute -top-2 -right-2 z-10">
    <span class="badge badge-primary animate-bounce-gentle">
      {{ $badge }}
    </span>
  </div>
  @endif

  @if($header || $icon || $title || $subtitle)
  <!-- カードヘッダー -->
  <div class="card-header {{ $padding === 'none' ? 'p-6' : '' }} {{ $footer ? 'mb-0' : '' }}">
    @if($header)
    {{ $header }}
    @else
    <div class="flex items-start gap-4">
      @if($icon)
      <div class="flex-shrink-0">
        @if(is_string($icon))
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white shadow-lg">
          <i class="{{ $icon }} text-lg"></i>
        </div>
        @else
        {{ $icon }}
        @endif
      </div>
      @endif

      @if($title || $subtitle)
      <div class="flex-1 min-w-0">
        @if($title)
        <h3 class="text-lg font-semibold text-neutral-900 mb-1 truncate">
          {{ $title }}
        </h3>
        @endif

        @if($subtitle)
        <p class="text-sm text-neutral-600 truncate">
          {{ $subtitle }}
        </p>
        @endif
      </div>
      @endif
    </div>
    @endif
  </div>
  @endif

  @if($slot && !$slot->isEmpty())
  <!-- カードボディ -->
  <div class="card-body {{ ($header || $icon || $title || $subtitle) ? 'pt-0' : '' }} {{ $footer ? 'pb-0' : '' }} {{ $padding === 'none' ? 'p-6' : '' }}">
    {{ $slot }}
  </div>
  @endif

  @if($footer || $actions)
  <!-- カードフッター -->
  <div class="card-footer {{ $padding === 'none' ? 'p-6 pt-0' : '' }}">
    @if($footer)
    {{ $footer }}
    @elseif($actions)
    <div class="flex items-center justify-end gap-3">
      {{ $actions }}
    </div>
    @endif
  </div>
  @endif

  @if($interactive && $hover)
  <!-- ホバーエフェクト用装飾 -->
  <div class="absolute inset-0 rounded-inherit bg-gradient-to-r from-mocha-500/10 to-sage-500/10 opacity-0 transition-opacity duration-300 hover:opacity-100 pointer-events-none"></div>
  @endif
</{{ $element }}>