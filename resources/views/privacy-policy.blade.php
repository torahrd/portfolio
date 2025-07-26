@extends('layouts.app')

@section('title', 'プライバシーポリシー')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">プライバシーポリシー</h1>
        
        <div class="bg-white shadow-lg rounded-lg p-6 space-y-6">
            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">1. 個人情報の収集について</h2>
                <p class="text-gray-700 leading-relaxed">
                    当サイトでは、ユーザーの皆様により良いサービスを提供するため、以下の個人情報を収集いたします。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">2. 収集する個人情報</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-2">
                    <li>氏名</li>
                    <li>メールアドレス</li>
                    <li>プロフィール情報</li>
                    <li>投稿内容</li>
                    <li>アクセスログ</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">3. 個人情報の利用目的</h2>
                <p class="text-gray-700 leading-relaxed">
                    収集した個人情報は、以下の目的で利用いたします。
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 mt-4">
                    <li>サービスの提供・運営</li>
                    <li>ユーザーサポート</li>
                    <li>サービスの改善</li>
                    <li>セキュリティの確保</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">4. 個人情報の管理</h2>
                <p class="text-gray-700 leading-relaxed">
                    当サイトでは、個人情報の漏洩、滅失、き損の防止その他の個人情報の安全管理のために必要かつ適切な措置を講じます。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">5. 個人情報の開示・訂正・利用停止</h2>
                <p class="text-gray-700 leading-relaxed">
                    ユーザーご本人からの個人情報の開示、訂正、利用停止のご要望に対しては、適切に対応いたします。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">6. お問い合わせ</h2>
                <p class="text-gray-700 leading-relaxed">
                    プライバシーポリシーに関するお問い合わせは、お問い合わせフォームよりお願いいたします。
                </p>
            </section>

            <div class="text-sm text-gray-500 mt-8 pt-6 border-t border-gray-200">
                <p>制定日: 2024年12月</p>
                <p>最終更新日: 2024年12月</p>
            </div>
        </div>
    </div>
</div>
@endsection 