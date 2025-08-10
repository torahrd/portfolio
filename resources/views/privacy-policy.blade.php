@extends('layouts.app')

@section('title', 'プライバシーポリシー')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">TasteRetreat プライバシーポリシー</h1>
        
        <div class="bg-white shadow-lg rounded-lg p-6 space-y-6">
            <div class="text-sm text-gray-500 mb-6">
                <p>最終更新日: 2025年8月10日</p>
            </div>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">1. 基本方針</h2>
                <p class="text-gray-700 leading-relaxed">
                    TasteRetreat(以下「本アプリ」)を運営する個人事業主(以下「当方」)は、利用者の個人情報保護を重要な責務と考え、個人情報保護法および関連法令を遵守し、利用者の個人情報を適切に取り扱います。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">2. 収集する情報の種類と目的</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">2.1 ユーザー登録情報</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                            <li><strong>収集情報:</strong> メールアドレス、ユーザー名、プロフィール画像(任意)</li>
                            <li><strong>利用目的:</strong> アカウント管理、サービス提供、利用者サポート、本人確認</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">2.2 投稿データ</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                            <li><strong>収集情報:</strong> 料理写真、テキスト投稿、評価・レビュー、店舗情報への投稿</li>
                            <li><strong>利用目的:</strong> SNS機能の提供、他利用者との情報共有、サービス改善</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">2.3 位置情報</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                            <li><strong>収集情報:</strong> GPS位置情報、投稿時の位置情報(任意)</li>
                            <li><strong>利用目的:</strong> 現在地周辺の店舗情報表示、地図機能の提供、投稿への位置情報付与</li>
                            <li><strong>取得方法:</strong> 利用者の明示的な同意に基づく取得</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">2.4 利用ログ・アクセス情報</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                            <li><strong>収集情報:</strong> アクセス日時、操作履歴、端末情報、IPアドレス</li>
                            <li><strong>利用目的:</strong> サービス提供、利用状況分析、不正利用防止、システム改善</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">2.5 Cookie・セッション情報</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                            <li><strong>収集情報:</strong> ログイン状態維持、設定情報、利用傾向</li>
                            <li><strong>利用目的:</strong> サービス利用の利便性向上、個人設定の保存</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">3. 情報の利用方法</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    収集した個人情報は、以下の目的で利用いたします:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li><strong>サービス提供・運営:</strong> アプリの基本機能提供、アカウント管理</li>
                    <li><strong>機能改善・開発:</strong> 利用状況分析によるサービス改善、新機能開発</li>
                    <li><strong>利用者サポート:</strong> 問い合わせ対応、技術的サポート</li>
                    <li><strong>安全確保:</strong> 不正利用防止、利用規約違反者の特定</li>
                    <li><strong>重要な通知:</strong> サービス変更、メンテナンス情報の連絡</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">4. 第三者サービスとの連携</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">4.1 Google Maps API</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                            <li><strong>提供データ:</strong> 位置情報、検索クエリ</li>
                            <li><strong>利用目的:</strong> 地図表示、現在地表示、店舗検索、ルート案内</li>
                            <li><strong>プライバシーポリシー:</strong> Google プライバシーポリシー</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">4.2 Cloudinary</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                            <li><strong>提供データ:</strong> アップロード画像、画像メタデータ</li>
                            <li><strong>利用目的:</strong> 画像処理・最適化、高速配信、画像データの安全な保存</li>
                            <li><strong>プライバシーポリシー:</strong> Cloudinary プライバシーポリシー</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">4.3 Google Analytics</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                            <li><strong>提供データ:</strong> アクセス情報、利用状況データ、Cookieを通じた匿名化された情報</li>
                            <li><strong>利用目的:</strong> サービス利用状況の分析、サービス改善、利用者行動の理解</li>
                            <li><strong>プライバシー保護:</strong> IPアドレスの匿名化、Cookie同意に基づく収集</li>
                            <li><strong>オプトアウト:</strong> ブラウザ設定またはCookie同意バナーから無効化可能</li>
                            <li><strong>プライバシーポリシー:</strong> Google プライバシーポリシー</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">5. 第三者への提供</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    以下の場合を除き、利用者の個人情報を第三者に提供することはありません:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li><strong>法令に基づく場合:</strong> 裁判所命令、警察の捜査協力要請等</li>
                    <li><strong>利用者の同意がある場合:</strong> 事前に明示的な同意を得た場合</li>
                    <li><strong>生命・身体の安全確保:</strong> 緊急性が高く、利用者の安全確保のため必要な場合</li>
                    <li><strong>業務委託:</strong> サービス提供に必要な範囲での適切な委託(秘密保持契約締結)</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">6. 情報の保存期間と削除</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li><strong>アカウント情報:</strong> 退会後1年間保存し、その後削除</li>
                    <li><strong>投稿データ:</strong> 利用者による削除またはアカウント削除まで保存</li>
                    <li><strong>アクセスログ:</strong> 取得から1年間保存し、その後削除</li>
                    <li><strong>位置情報:</strong> 利用目的達成後、速やかに削除</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">7. 利用者の権利</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">7.1 開示・訂正・削除</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                            <li><strong>開示請求:</strong> 保有する個人情報の開示請求</li>
                            <li><strong>訂正・追加・削除:</strong> 個人情報の訂正、追加、削除の請求</li>
                            <li><strong>利用停止:</strong> 個人情報の利用停止・消去の請求</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">7.2 具体的な手続き</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                            <li><strong>アプリ内設定:</strong> プロフィール編集、投稿削除、アカウント削除</li>
                            <li><strong>お問い合わせ:</strong> 下記連絡先への直接連絡</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">8. セキュリティ対策</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li><strong>技術的対策:</strong> SSL暗号化通信、アクセス制御、定期的なセキュリティ更新</li>
                    <li><strong>物理的対策:</strong> 機器の適切な管理、盗難・紛失対策</li>
                    <li><strong>人的対策:</strong> 秘密保持の徹底、委託先への適切な監督</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">9. 国際的なデータ転送</h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    本アプリは、サービス提供のため以下の国・地域へ個人データを転送する場合があります:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li><strong>米国:</strong> Cloudinary、Google Maps API等のサービス利用</li>
                    <li><strong>EU:</strong> Cloudinaryのデータセンター利用</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">10. 未成年者の利用</h2>
                <p class="text-gray-700 leading-relaxed">
                    13歳未満の利用者の個人情報は、保護者の明示的な同意がない限り収集いたしません。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">11. お問い合わせ先</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li><strong>アプリ名:</strong> TasteRetreat</li>
                    <li><strong>X(Twitter):</strong> @HToranosukeより</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">12. プライバシーポリシーの変更</h2>
                <p class="text-gray-700 leading-relaxed">
                    本ポリシーは、法令の変更やサービス内容の変更に応じて更新することがあります。
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">13. 関連するプライバシーポリシー</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li>Google Maps API プライバシーポリシー</li>
                    <li>Google Analytics プライバシーポリシー</li>
                    <li>Cloudinary プライバシーポリシー</li>
                </ul>
            </section>

            <div class="text-sm text-gray-500 mt-8 pt-6 border-t border-gray-200">
                <p>制定日: 2025年7月26日</p>
                <p>最終更新日: 2025年8月10日</p>
            </div>
        </div>
    </div>
</div>
@endsection 