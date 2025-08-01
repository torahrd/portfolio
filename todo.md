# タスク管理

## 現在のタスク

### 🔴 緊急タスク
- [ ] **Phase 16-2: デプロイ後重要修正**
  - [x] 投稿作成画面で店舗名を候補から必ず選択させる仕様変更
  - [x] プロフィールリンク機能の実装（feature/profile-link-generation ブランチ）
  - [x] 投稿一覧画面で新着タブに移動できない問題修正 
  - [ ] 投稿詳細ページで投稿の編集・削除を3点リーダーからボタンに変更
  - [ ] アカウント削除機能の動作確認・修正
- [ ] **Phase 16-4: セキュリティ対策実装（v0.2計画に基づく）**
  - [x] 通知メッセージのXSS修正（FollowRequestNotification.php、notification-card.blade.php）
  - [x] **Google Places APIセキュリティ対策実装（APIエンドポイント認証追加の一環）**
    - [x] Google Cloud Console設定（デュアルAPIキー作成）
    - [x] APIプロキシパターン実装
    - [x] 403エラー解消（Places API仕様制約対応）とAPIキー露出防止
    - [x] /api/shops/map-data、/api/search/suggestionsの認証強化
    - [x] **詳細実装手順**
      - [x] **現状の問題と原因（リサーチ結果に基づく）**
      - [x] 403エラー: Places APIの根本的なアーキテクチャ制約（仕様による制約）
      - [x] APIキー露出: クライアントサイドで見える状態
      - [x] セキュリティリスク: 第三者による不正利用可能
      - [x] 2025年の変更: Places API (Legacy)がレガシー化、Places API (New)への移行必須
    - [x] **解決方針（デュアルAPIキー戦略）**
      - [x] クライアント用APIキー: HTTPリファラー制限 + Maps JavaScript API + Places API
      - [x] サーバー用APIキー: IP制限 + Places API (New)
      - [x] APIプロキシパターン: クライアント → 自社サーバー → Google API
    - [x] **事前確認**
      - [x] AWS Lightsail Elastic IP: 18.178.239.220
      - [x] Google Cloud Consoleアクセス権: 確認済
      - [x] ビリング状態確認: 2025年変更によりビリング必須
      - [x] バックアップ: .envファイルのコピー作成
    - [x] **Phase 3-1: Google Cloud Console設定**
      - [x] クライアント用APIキー作成（HTTPリファラー制限）
      - [x] サーバー用APIキー作成（IP制限: 18.178.239.220）
      - [x] キー作成後、値をメモ
    - [x] **Phase 3-2: 環境設定**
      - [x] .envファイルに新キー追加
      - [x] config/google.php修正
      - [x] config/services.php修正
      - [x] ローカルで動作確認
    - [x] **Phase 3-3: プロキシ実装**
      - [x] GooglePlacesProxyController作成
      - [x] 認証ミドルウェア適用
      - [x] レート制限実装
      - [x] APIルート追加
    - [x] **Phase 3-4: 既存コード修正**
      - [x] GooglePlacesService.php修正（サーバー用APIキー使用）
      - [x] フロントエンドAPI呼び出し修正（shop-search.js）
      - [x] map/index.blade.php修正（確認済み - 修正不要）
    - [x] **Phase 3-5: テスト**
      - [x] 403エラー解消確認
      - [x] APIキー非露出確認
      - [x] 全機能動作確認
  - [x] **Phase 16-4-1: CORS設定修正（ローカル）**
    - [x] config/cors.phpで本番ドメイン追加
    - [x] allowed_originsに'https://taste-retreat.com'設定
    - [x] curl動作確認
  - [x] **Phase 16-4-2: プライバシーポリシー追加（ローカル）**
    - [x] privacy-policy.blade.php作成
    - [x] ルート追加（/privacy-policy）
    - [x] フッターリンク追加（フッターコンポーネント化完了）
    - [x] 個人情報保護法準拠内容記載
  - [ ] **Phase 16-4-3: 本番環境反映**
    - [ ] git pull + composer install
    - [ ] キャッシュクリア実行
    - [ ] CORS/プライバシーポリシー確認
  - [ ] **Phase 16-4-4: 本番セキュリティヘッダー設定**
    - [ ] bitnami-ssl.confバックアップ
    - [ ] HSTS設定追加
    - [ ] CSP設定追加  
    - [ ] Apache再起動・確認
  - [x] **Phase 16-4-5: ログ機密情報修正（ローカル）**
    - [x] PostController.php修正
    - [x] $request->all()使用箇所特定
    - [x] 機密情報除外ロジック追加
  - [x] **Phase 16-4-6: 2要素認証実装・UX向上のため無効化（ローカル）**
    - [x] TwoFactorAuthenticatableトレイト追加
    - [x] Fortify設定完了
    - [x] 2FAビュー・ルート作成（基本設定完了）
    - [x] 動作確認
    - [x] **UX向上のため2FA無効化を決定**
      - [x] 理由：料理投稿アプリには過剰なセキュリティ、毎回の認証が不便
      - [x] モバイル対応：スマホでのログイン時の不便さ
      - [x] ユーザー層：一般ユーザー向けの適切なセキュリティレベルに調整
      - [x] 代替策：強力なパスワードポリシー、ログイン試行制限、パスワード確認機能
      - [x] config/fortify.phpでFeatures::twoFactorAuthentication()を無効化
  - [x] **Phase 16-4-7: Mailgun設定完了**
    - [x] symfony/mailgun-mailerパッケージ追加
    - [x] config/mail.php設定更新（Mailgun設定有効化）
    - [x] config/services.php設定更新（ドメイン設定）
    - [x] テスト用artisanコマンド作成（mail:test-mailgun、mail:test-mailgun-sandbox、mail:test-mailgun-sandbox-domain）
    - [x] パッケージインストール完了
    - [x] 新しいAPIキー生成・設定完了
    - [x] メール送信テスト成功（taste-retreat.comドメイン）
    - [x] 動作確認完了
  - [ ] **Phase 16-4-8: バックアップシステム構築**
    - [ ] spatie/laravel-backupインストール
    - [ ] 設定ファイル作成
    - [ ] cron設定
    - [ ] 動作確認
  - [ ] **本番環境セキュリティヘッダー追加**
    - [ ] **Strict-Transport-Security (HSTS) の追加**
      - [ ] Apache設定ファイルにHSTSヘッダー追加
      - [ ] 設定値: `max-age=31536000; includeSubDomains`
      - [ ] 本番環境での設定確認
    - [ ] **Content Security Policy (CSP) の段階的実装**
      - [ ] Phase 1: 監視モードでのCSP実装（Report-Only）
      - [ ] Phase 2: 本格的なCSP実装（ブロックモード）
      - [ ] 現在のアプリケーションに合わせたCSP設定調整
      - [ ] Google Maps API、Cloudinary、Google Fonts対応
    - [ ] **セキュリティヘッダー設定の確認**
      - [ ] ブラウザ開発者ツールでの確認
      - [ ] オンラインツールでの確認
      - [ ] 動作確認（サイトの正常動作確認）
  - [ ] ログの機密情報修正（PostController.phpの$request->all()使用箇所）
  - [ ] メンション機能の修復（現在動作していない）
  - [x] **Phase 16-4-9: 基本セキュリティヘッダー実装（2週以内）**
    - [x] **Strict-Transport-Security**: `max-age=31536000; includeSubDomains`
    - [x] **X-Content-Type-Options**: `nosniff`
    - [x] **X-Frame-Options**: `DENY`
    - [x] **X-XSS-Protection**: `1; mode=block`
    - [x] **Referrer-Policy**: `strict-origin-when-cross-origin`
    - [x] ミドルウェア作成: `middleware/AddSecurityHeaders.php`
    - [x] X-Powered-Byヘッダー削除（AppServiceProvider.php）
    - [x] 効果: XSS・クリックジャッキング基礎防御
  - [ ] **Phase 16-4-13: CSP実装（unsafe-evalなし対応）（1週以内）**
    - [x] **現状の問題分析**
      - [x] Alpine.jsが`unsafe-eval`を必要とする問題を特定
      - [x] 既存のAddSecurityHeadersミドルウェアとの競合を確認
      - [x] spatie/laravel-cspパッケージのバージョン制約を確認
    - [x] **調査結果に基づく根本問題の特定**
      - [x] CSPビルドの制約違反を特定（x-model、x-on、x-dataの関数呼び出し）
      - [x] 検索機能のAPIリクエスト送信失敗の原因を特定
      - [x] 「変な線」問題の原因を特定（CSPビルドが解釈できないHTML要素）
    - [ ] **フェーズ1: 緊急復旧（セキュリティ影響なし）**
      - [x] CSPビルドの一時無効化（@alpinejs/csp → 通常のAlpine.js）
      - [x] 検索機能の即座復旧
      - [x] APIリクエスト送信の動作確認
      - [x] 「変な線」問題の解消確認
      - [x] 全機能の動作確認（検索・モーダル・コメント）
    - [ ] **フェーズ2: 段階的CSP対応（セキュリティ強化）**
      - [ ] 検索コンポーネントのCSP対応
        - [ ] shop-search.jsのAlpine.data()化
        - [ ] search-bar.jsのAlpine.data()化
        - [ ] 投稿作成画面での動作確認
      - [ ] 他のコンポーネントのCSP対応
        - [ ] modal.jsのCSP対応
        - [ ] comment-section.jsのCSP対応
      - [ ] CSPビルドの段階的再導入
        - [ ] 各コンポーネントの動作確認
        - [ ] CSPエラーの監視
    - [ ] **フェーズ3: 完全CSP対応（セキュリティ最大化）**
      - [ ] 全コンポーネントのCSP対応完了
      - [ ] 残存するCSP違反の解消
      - [ ] 最終検証（全機能動作確認・CSPエラー完全解消）
    - [ ] **Report-Onlyモードでの段階的実装**
      - [ ] Content-Security-Policy-Report-Onlyヘッダーの設定
      - [ ] ブラウザコンソールでの違反監視
      - [ ] 段階的なCSP設定の調整
    - [ ] **Alpine.js CSPビルド対応**
      - [x] @alpinejs/cspパッケージのインストール
      - [x] app.jsでの初期化設定変更
      - [ ] 既存コンポーネントのAlpine.data()化（動作確認未完了）
        - [ ] search-bar.jsのAlpine.data()化
        - [ ] shop-search.jsのAlpine.data()化
        - [ ] modal.jsのAlpine.data()化
        - [ ] comment-section.jsのAlpine.data()化
      - [ ] Bladeテンプレートの修正（インライン式→外部API）（動作確認未完了）
        - [ ] x-dataの静的文化（インライン式→Alpine.data()）
        - [ ] 動的評価の回避（x-on:click="count++" → x-on:click="increment"）
        - [ ] 条件式の外部化（x-show="!isOpen" → x-show="isClosed"）
    - [ ] **Google Maps API・Cloudinary対応**
      - [ ] 必要なドメインの特定と許可設定
      - [ ] script-src、img-src、connect-srcの最適化
      - [ ] 動的読み込み対応
    - [ ] **CSP設定の最適化**
      - [ ] unsafe-evalの完全削除
      - [ ] nonceベースの許可設定
      - [ ] Google Maps API対応の維持
    - [ ] **動作確認とテスト**
      - [ ] 検索機能の正常動作確認
      - [ ] モーダル機能の正常動作確認
      - [ ] コメント機能の正常動作確認
      - [ ] CSPエラーの完全解消確認
    - [ ] **本格CSP実装**
      - [ ] Report-Onlyからブロックモードへの移行
      - [ ] 最終的なCSP設定の確定
      - [ ] 動作確認とエラー解消
  - [ ] **Phase 16-4-10: 認証セキュリティ強化（3週以内）**
    - [ ] **強力パスワードポリシー実装**
      - [ ] 8文字以上、大文字/小文字/数字/記号必須
      - [ ] 辞書語句TOP10k禁止
      - [ ] 強度メーター表示、変更時に全端末ログアウト
    - [ ] **ログイン試行制限実装**
      - [ ] 5回失敗 ⇒ 15分ロック（アカウント）
      - [ ] IP単位10回失敗 ⇒ 1時間ロック
      - [ ] 変則ログインをログに記録＋メール通知
  - [ ] **Phase 16-4-11: ファイルアップロード対策（1ヶ月以内）**
    - [ ] 10MB上限、MIME/拡張子ホワイトリスト
    - [ ] 画像サイズ100×100～4000×4000
    - [ ] ウイルススキャン＆文字列検査
    - [ ] 10回/分/ユーザーのレート制限
  - [ ] **Phase 16-4-12: インシデント対応準備（1.5ヶ月以内）**
    - [ ] 連絡体制・通知テンプレート
    - [ ] 個人情報漏洩時の報告手順（3-5日以内）
    - [ ] データ漏洩対応フローチャート

### 🟡 重要タスク
- [ ] **Phase 16-3: UI/UX改善**
  - [ ] 店舗詳細画面で投稿タブのみカードのサイズが違うUI修正
  - [ ] Google Mapのスタイル適用完了
  - [ ] 通知のアイコンデザイン修正
  - [ ] アカウント設定のデータエクスポート機能削除
- [ ] **メンション機能の修正**
  - [ ] 現在メンション機能が動作していません。
  - [ ] **XSS対策の実装**
    - [ ] コメントのメンション機能で{!! !!}を使用している箇所の修正
    - [ ] ユーザー入力の適切なエスケープ処理の実装
    - [ ] メンション機能修復後のXSS対策確認

### 🟢 通常タスク


## 完了済みタスク

完了済みタスクは全て todo-archive.md で確認してください。

## 一時停止中のタスク

現在一時停止中のあるタスクはありません。

## 問題のあるタスク

現在問題のあるタスクはありません。

## 今後の予定タスク

- [ ] **Phase 17: 新機能実装**
  - [ ] **プロフィールリンク機能のHTML重複ID問題の根本解決**
    - [ ] profile/show.blade.phpでモバイル用・PC用の重複コンポーネント問題解決
    - [ ] モーダルの共通化実装（1つのモーダルを2つのカードで共有）
    - [ ] W3C HTML標準準拠（id属性重複の完全排除）
    - [ ] レスポンシブデザインベストプラクティス適用
    - [ ] アクセシビリティ向上（予期しない動作の防止）
    - [ ] 保守性向上（1つのモーダル管理に統一）
  - [ ] サジェストUI・Alpine関数・key設計の共通化リファクタリングを検討（画面ごとのprops/mode分岐で柔軟に対応）
  - [ ] 投稿にカテゴリ機能を搭載し、それを用いた検索機能を追加
  - [ ] エリアに絞った検索機能を実装
  - [ ] フォロー・アンフォローの非同期UI更新実装
    - リロード/リダイレクト方式から非同期での即時UI反映に変更
    - ボタン表示・カウント・その他UI要素の即時更新
- [ ] プライベートアカウントの投稿および「非表示」設定の投稿は、未認可ユーザーから閲覧できないようにする
  - [ ] 投稿詳細・一覧・APIレスポンスでの認可ロジック強化
  - [ ] Blade/JS側でのUI非表示制御
  - [ ] テスト・動作確認
- [ ] **プロフィール画面の左カード高さ調整機能**
  - [ ] 左カード（プロフィールカード）の高さを右カラム（投稿一覧）の最下端に合わせる動的調整機能
  - [ ] 無限スクロールでの投稿追加時の高さ自動再計算
  - [ ] 既存のsticky top-16動作を維持しながらの実装
  - [ ] ページ全体のスクロール時の高さ同期
  - [ ] 右カラムが短い場合は現在の最小高さ設定を維持
  - [ ] レスポンシブ対応（デスクトップでのみ動作、モバイルは現状維持）
  - [ ] パフォーマンス最優化（リアルタイム監視とDOM操作の効率化）
  - [ ] 実装理由：UI/UXの統一性向上、視覚的バランスの改善
- [ ] エラーや通知などの表示内容の最適化（専門用語の排除・一般ユーザー向け表現）
- [ ] プロフィールリンク再生成機能の実装（有効期間中でも再生成・手動無効化後の再生成を可能にする拡張）

- [ ] **Phase 18: Google Maps API最適化・機能強化**
  - [ ] Google Maps JavaScript APIの警告対応
    - [ ] Marker非推奨警告の対応（AdvancedMarkerElementへの移行）
    - [ ] loading=async未使用警告の対応
    - [ ] 最新のGoogle Maps API仕様への完全対応
  - [ ] 地図スタイルのカスタマイズ
    - [ ] カスタムマーカーアイコンの実装（店舗タイプ別アイコン）
    - [ ] 地図テーマのカスタマイズ（ダークモード対応）
    - [ ] マーカークラスタのデザインカスタマイズ
  - [ ] インタラクティブ機能の強化
    - [ ] ホバー時の店舗情報表示（店舗名・営業時間・評価等）
    - [ ] マーカークリック時の詳細情報ウィンドウ
    - [ ] 地図上での検索機能（現在地周辺検索）
    - [ ] ルート案内機能の実装
  - [ ] パフォーマンス最適化
    - [ ] ビューポート内レンダリング最適化
    - [ ] マーカーの遅延読み込み
    - [ ] 地図データの段階的読み込み
  - [ ] レスポンシブ対応の確認・改善
    - [ ] スマホ・タブレット幅での地図高さ・UI崩れの確認
    - [ ] モバイル専用の地図操作UI
    - [ ] タッチ操作の最適化
  - [ ] セキュリティ強化
    - [ ] APIキーの制限設定（ドメイン制限・リファラー制限）
    - [ ] 使用量監視ダッシュボードの実装
    - [ ] 異常検知・アラート機能の実装
  - [ ] データベース最適化
    - [ ] 店舗座標データのインデックス最適化
    - [ ] 地理空間クエリの実装（PostGIS等）
    - [ ] 近隣店舗検索の高速化
  - [ ] ユーザビリティ改善
    - [ ] 地図上での店舗お気に入り機能
    - [ ] 地図表示設定の保存機能
    - [ ] 地図履歴機能（最近見た店舗）
  - [ ] 統合機能の強化
    - [ ] 投稿作成時の地図連携強化
    - [ ] 店舗詳細ページでの地図表示
    - [ ] ユーザープロフィールでの訪問店舗地図表示

- [ ] **将来のセキュリティ強化（ユーザー数増加後）**
  - [ ] 高度な監視システム導入
  - [ ] パスワードレス認証（WebAuthn等）
  - [ ] 行動パターン分析によるボット検出
  - [ ] API機能別レート制限
  - [ ] ゼロトラストアーキテクチャ
  - [ ] AIリアルタイム脅威検出
  - [ ] デバイス個体管理
  - [ ] 24/365セキュリティオペレーションセンター

---

## 今後のアップデート方針（画像アップロード機能）
- 複数画像アップロード（サブ画像ギャラリー、ドラッグ＆ドロップ、並び替え等）は将来的な拡張候補。
- 現状は「1枚のみアップロード」でリリースし、リッチなUI/UXはリリース後の段階的アップデートで対応。

## 環境情報

### 現在の環境状況
- **Docker環境**: 正常稼働中
  - portfolio_app (PHP 8.2.28)
  - portfolio_nginx (Nginx 1.18.0)
  - portfolio_mysql (MySQL)
- **Laravel**: 10.48.29
- **データベース**: マイグレーション完了（23個）
- **フロントエンド**: Vite + Tailwind CSS
- **アクセスURL**: http://localhost
- **本番ドメイン**: https://taste-retreat.com

### 確認済み機能
- アプリケーション起動: ✅
- データベース接続: ✅
- マイグレーション: ✅
- フロントエンドビルド: ✅
- Webサーバー: ✅

### ルールファイル構成（8つ）
- `.cursor/rules/core-rules.mdc`: 基本開発ルール（常に適用）
- `.cursor/rules/beginner-guidelines.mdc`: 初心者向け解説方針（常に適用）
- `.cursor/rules/laravel-backend.mdc`: Laravelバックエンド開発時のみ参照（AutoAttached）
- `.cursor/rules/tailwind-frontend.mdc`: フロントエンド開発時のみ参照（AutoAttached）
- `.cursor/rules/error-handling.mdc`: エラー対応時のみ参照（AgentRequested）
- `.cursor/rules/task-management.mdc`: タスク管理時のみ参照（AgentRequested）
- `.cursor/rules/knowledge-management.mdc`: 技術的ナレッジベース管理時のみ参照（AgentRequested）
- `.cursor/rules/qa-knowledge.mdc`: 開発Q&A記録時のみ参照（AgentRequested）

## 現在のコンポーネント状況

### x-atoms.avatar コンポーネント
- **現状**: 機能改善完了
- **機能**: 
  - アバター画像表示（`$user->avatar_url`）
  - 頭文字表示（日本語対応、`AvatarHelper::getInitial()`）
  - サイズ調整（small, default, large）
  - プライベートアイコン表示（統合済み）
- **使用箇所**: 
  - `post-card.blade.php`（size="small"）
  - その他のコンポーネントで適切に使用中

### 通知ドロップダウンと/notifications一覧ページのUI統一（将来実装予定）
  - 現状は/notificationsへのリンクのみ設置
  - ドロップダウンと一覧の両立はUI不整合リスクがあるため、今後の課題として記録

## メモ



- **重要な教訓**: 段階的アプローチで一歩ずつ進める、動作確認の必須化
- コメント削除のダイアログは標準confirm()のため、カスタムモーダル化しない限りautofocus制御不可
- UIアクセシビリティ改善は段階的に進める
- Google Mapsの警告（Marker非推奨、loading=async未使用）は現状の地図表示には影響なし。今後のリファクタリングで対応予定。
- レスポンシブ対応（スマホ・タブレット幅での地図高さ・UI崩れ）は未確認。必要に応じてTailwindのレスポンシブクラスや@mediaクエリで調整予定。
- リファクタリングの基本原則：1コンポーネント1機能、拡張性より単純性、可読性最優先、保守性重視、Tailwindクラス直接記述
- **新しいルール体系**: 8つのルールファイルで包括的な開発ガイドラインを構築
- **ルール見落とし防止**: 作業開始前に必ず全てのルールファイルを参照し、適用状況を確認