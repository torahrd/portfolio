@extends('layouts.app')

@section('title', 'お問い合わせ')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">お問い合わせ</h1>
        
        <div class="bg-white shadow-lg rounded-lg p-6 space-y-6">
            <div class="text-gray-700 leading-relaxed mb-6">
                <p>TasteRetreatに関するお問い合わせは、以下の方法でお願いいたします。</p>
            </div>

            <section class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">お問い合わせ方法</h2>
                
                <div class="bg-gray-50 border-l-4 border-blue-500 p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">X (Twitter) でのお問い合わせ</h3>
                    <p class="text-gray-700 mb-3">
                        最も迅速に対応可能です。以下のアカウントまでDMまたはメンションでご連絡ください。
                    </p>
                    <a href="https://twitter.com/HToranosukeより" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                        @HToranosukeより
                    </a>
                </div>

                <div class="bg-gray-50 border-l-4 border-green-500 p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">メールでのお問い合わせ</h3>
                    <p class="text-gray-700 mb-3">
                        以下のメールアドレスまでご連絡ください。
                    </p>
                    <a href="mailto:support@taste-retreat.com" 
                       class="inline-flex items-center text-green-600 hover:text-green-800 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        support@taste-retreat.com
                    </a>
                </div>
            </section>

            <section class="space-y-4 mt-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">お問い合わせ内容の例</h2>
                <ul class="list-disc list-inside space-y-2 text-gray-700">
                    <li>サービスの使い方に関するご質問</li>
                    <li>不具合・バグのご報告</li>
                    <li>機能改善のご要望</li>
                    <li>アカウントに関するお問い合わせ</li>
                    <li>その他、TasteRetreatに関するご意見・ご感想</li>
                </ul>
            </section>

            <section class="space-y-4 mt-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">よくあるご質問</h2>
                
                <div class="space-y-4">
                    <details class="border border-gray-200 rounded-lg p-4">
                        <summary class="font-semibold text-gray-800 cursor-pointer">
                            アカウントを削除したいのですが？
                        </summary>
                        <p class="mt-3 text-gray-700">
                            設定画面から「アカウント削除」を選択いただくか、上記お問い合わせ先までご連絡ください。
                        </p>
                    </details>

                    <details class="border border-gray-200 rounded-lg p-4">
                        <summary class="font-semibold text-gray-800 cursor-pointer">
                            投稿した内容を削除できますか？
                        </summary>
                        <p class="mt-3 text-gray-700">
                            各投稿の編集メニューから削除が可能です。削除した投稿は復元できませんのでご注意ください。
                        </p>
                    </details>

                    <details class="border border-gray-200 rounded-lg p-4">
                        <summary class="font-semibold text-gray-800 cursor-pointer">
                            24店舗の制限を変更できますか？
                        </summary>
                        <p class="mt-3 text-gray-700">
                            24という数字は日本の24節気に由来する、TasteRetreatのコンセプトの核となる部分です。
                            厳選することで本当に大切なお店との出会いを大切にしていただくため、制限の変更は予定しておりません。
                        </p>
                    </details>

                    <details class="border border-gray-200 rounded-lg p-4">
                        <summary class="font-semibold text-gray-800 cursor-pointer">
                            位置情報が正しく取得されません
                        </summary>
                        <p class="mt-3 text-gray-700">
                            ブラウザの設定で位置情報の許可をご確認ください。また、HTTPSでアクセスしているかもご確認ください。
                        </p>
                    </details>
                </div>
            </section>

            <section class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-2">返信について</h3>
                <p class="text-blue-700 text-sm">
                    お問い合わせへの返信は、通常1-3営業日以内に行います。
                    お急ぎの場合は、X (Twitter) でのご連絡をおすすめいたします。
                </p>
            </section>
        </div>
    </div>
</div>
@endsection