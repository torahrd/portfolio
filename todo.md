# TasteRetreat タスク管理

## 🎯 プロジェクト目標
- **最優先**: セキュリティリスクの解消
- **中期目標**: 収益化の実現（1年以内）
- **現在のフェーズ**: MVP完成に向けたセキュリティ強化

---

## 🔴 Phase 1: 緊急セキュリティ対応（1週間）

### 1.0 セッション暗号化の有効化 ✅ 完了
**現状**: セッションが平文で保存されている（'encrypt' => false）
**影響**: 設定変更のみ、全ユーザー再ログイン必要
**TDD方式で実装**

#### 🔴 Red（テスト作成）✅
- [x] tests/Feature/SessionEncryptionTest.php作成
  - [x] セッション暗号化が有効になっているかのテスト
  - [x] 暗号化後もセッションが正しく動作することのテスト

#### 🟢 Green（最小実装）✅
- [x] config/session.php の 'encrypt' を true に変更
- [x] ローカル環境でテスト実行・動作確認
- [x] 全ユーザー再ログインの影響を確認

#### 🟡 Refactor（品質改善）✅
- [x] 本番環境用の設定確認
- [x] デプロイ手順書に再ログイン必要の旨を追記

#### ⚪ Commit（記録）✅
- [x] MCPメモリに手順記録（session-encryption-implementation-2025-08）
- [x] git commit & push（feature/session-encryptionブランチ）
- [x] 本番環境反映済み（2025-08-15）

### 1.1 本番環境設定確認 ✅ 完了
**現状**: APP_DEBUG等の本番設定が未確認
**影響**: セキュリティリスクの即座の解消
**作業時間**: 10分

#### タスク詳細 ✅
- [x] 本番環境の.env確認（APP_DEBUG=false）✅
- [x] SESSION_SECURE_COOKIE=true追加 ✅
- [x] APP_ENV=production確認 ✅
- [x] 設定キャッシュクリア実行 ✅
**完了日**: 2025-08-15

### 1.2 CommentSection権限修正（CSP完全対応は完了済み）
**現状**: 投稿作者がコメントを削除できない
**影響**: 既存機能の修正、影響範囲限定的
**作業時間**: 30分
**TDD方式で実装**

#### 🔴 Red（テスト作成）✅ 完了
- [x] tests/Feature/CommentSectionCspTest.php作成
  - [x] 新規コメント投稿のテスト
  - [x] コメント返信機能のテスト
  - [x] コメント削除機能のテスト
  - [x] CSPエラーが発生しないことのテスト（手動確認用）
- [x] Factory作成（Shop, Post, Comment）
- [x] テストが正しく失敗することを確認

#### 🟢 Green（最小実装）✅ 完了
- [x] commentSection.jsのCSP対応完了
  - [x] 複雑な条件式をメソッド化（すでに実装済み）
  - [x] x-modelの代替実装（:value + @input使用）
  - [x] イベントハンドラーのメソッド化（実装済み）
- [x] app.jsでcommentSectionを有効化
- [x] API対応（/commentsエンドポイント追加）
  - [x] CommentController::storeGeneric実装
  - [x] JSON/AJAX対応の削除メソッド修正
- [x] テスト全6項目パス確認

#### 🟡 Refactor（品質改善）✅ 完了
- [✅] エラーハンドリングの統一（トランザクション、ログ記録）
- [✅] コードの最適化（重複コード削除、ヘルパーメソッド追加）
- [✅] Laravel Pintでの整形
- [✅] ブラウザでの動作確認（問題修正として実施済み）
  - [✅] コメント投稿・返信・削除が動作
  - [✅] CSPエラーが解消

#### 🔵 Enhancement（非同期処理の完全実装）✅ 完了
**目的**: モダンなUXを実現する完全非同期コメントシステム
**ベストプラクティス**: 
- insertAdjacentHTMLによるDOM操作（Vue.jsライクな実装）
- Axiosパターンのエラーハンドリング
- スクロール位置の保持

##### タスク詳細
- [x] フォームの非同期処理修正
  - [x] comment-formに@submit.prevent追加
  - [x] バリデーションエラーのアラート表示修正
- [x] DOM操作による部分更新実装
  - [x] submitCommentの非同期化（リロード削除）
  - [x] submitReplyの非同期化（リロード削除）
  - [x] insertAdjacentHTMLで新規コメント追加
- [x] UX改善
  - [x] ローディング状態の表示
  - [x] スクロール位置の保持
  - [x] アニメーション追加（fade-in効果）
- [x] エラーハンドリング強化
  - [x] ネットワークエラーの適切な表示
  - [x] バリデーションエラーの個別表示
- [ ] テスト追加（後日実施）
  - [ ] 非同期処理のE2Eテスト
  - [ ] DOM更新の確認テスト

#### ⚪ Commit（記録）✅ 完了
- [x] _docs/dev-log/2025-01-16_16-30_comment-async-csp.md作成
- [x] テスト実行確認（php artisan test）
- [ ] git commit & push（実施中）

### 1.3 CommentSection権限修正 ✅ 完了
**現状**: 投稿作者がコメントを削除できない
**影響**: 既存機能の修正、影響範囲限定的  
**作業時間**: 30分
**TDD方式で実装**

#### 🔴 Red（テスト作成）✅
- [x] tests/Feature/CommentSectionCspTest.phpの修正
  - [x] 投稿作者もコメント削除可能なテストケース追加
  - [x] 第三者削除不可のテストケース追加

#### 🟢 Green（最小実装）✅
- [x] CommentController::destroyの権限ロジック確認（既に修正済み）
- [x] フィールド名統一確認（既にbody統一済み）
- [x] ルート確認（適切な設定済み）

#### 🟡 Refactor（品質改善）✅
- [x] Laravel Pintでの整形
- [x] 権限テスト全通過確認

#### ⚠️ 発見された問題 ✅ 解決済み
- [x] コメント文字数制限バリデーション不具合修正（500エラー→422エラー）
  - [x] CommentStoreRequest.phpのmax:1000→max:200に修正
  - [x] データベース制約（200文字）とバリデーションルールの整合性確保

#### ⚪ Commit（記録）✅ 完了
- [x] git commit & push（feature/comment-permission-fixブランチ）
- [x] GitHubでマージ済み
**完了日**: 2025-08-16

### 1.4 プライベート投稿の権限管理と投稿編集バグ修正 ✅ 完了
**現状**: PostPolicyは作成済みだが未適用、誰でも閲覧可能な状態 → 解決済み
**影響**: 重大なプライバシー問題 → 解消
**作業時間**: 5時間（テスト原因究明＋追加修正＋CSP対応含む）
**TDD方式で実装**

#### 🔴 Red（テスト作成）✅
- [x] tests/Feature/PrivatePostAuthorizationTest.php作成
  - [x] 非公開投稿が他ユーザーから見えないテスト
  - [x] 本人は常に閲覧可能なテスト
  - [x] 投稿一覧でのフィルタリングテスト（DB + HTTPレスポンス）
  - [x] 編集・削除権限のテスト
  - [x] 403エラー画面の表示確認

#### 🟢 Green（最小実装）✅
- [x] PostController::showでauthorize('view', $post)追加
- [x] PostController::editでauthorize('update', $post)追加
- [x] PostController::destroyでauthorize('delete', $post)追加（既存）
- [x] PostController::indexでプライベート投稿のフィルタリング
- [x] private_statusのboolean形式への統一（PostFactory含む）
- [x] PostController::updateでの既存shop_id保持処理追加
- [x] 投稿詳細での非公開ステータス表示追加
- [x] 店舗選択のAlpine.js CSP対応（$event使用）
- [x] バリデーションメッセージの日本語化

#### 🟡 Refactor（品質改善）✅
- [x] Laravel Pintでコード整形
- [x] テスト原因究明と適切な修正
- [x] 包括的テスト（DB + HTTPレスポンス両方）
- [x] Alpine.js CSPビルド対応（getterメソッド削除、$event方式採用）
- [x] lines-and-columnsパッケージのダミーファイル作成
- [x] 投稿作成画面のコンポーネント化（統一）
- [x] UI改善（キャンセルボタン追加、ボタン配置統一）

#### ⚪ Commit（記録）✅
- [x] feature/post-edit-private-status-fixブランチ作成
- [x] TDDサイクル完全実行
- [x] MCPメモリ記録（ui-button-placement-guideline、ui-modal-usage-guideline）
- [x] git commit & push（実施予定）

**完了日**: 2025-08-17

### 1.5 簡易XSS対策 ✅ 完了
**現状**: {!! $comment->body_with_mentions !!}で生HTML出力 → 修正済み
**影響**: XSS脆弱性の可能性 → 解消
**作業時間**: 1時間
**TDD方式で実装**

#### 🔴 Red（テスト作成）✅
- [x] tests/Feature/XssProtectionTest.php作成
  - [x] 危険なHTMLタグが除去されるテスト
  - [x] メンション機能が動作するテスト
  - [x] XSS攻撃パターンのテスト（5種類）

#### 🟢 Green（最小実装）✅
- [x] Comment::getBodyWithMentionsAttributeでstrip_tags実装
- [x] htmlspecialchars()でHTMLエンティティ化
- [x] メンション部分のみspan要素でラップ

#### 🟡 Refactor（品質改善）✅
- [x] 他の生HTML出力箇所の確認
- [x] ブラウザでの動作確認完了
- [x] RefreshDatabase違反の修正（7ファイル）

#### ⚪ Commit（記録）✅
- [x] MCPメモリに記録（refresh-database-violation-fix-2025-08-17）
- [x] git commit & push（feature/xss-protectionブランチ）
**完了日**: 2025-08-17

### 1.5.5 CSP unsafe-eval削除 ✅ 完了
**現状**: unsafe-evalがCSPに含まれている → 削除済み
**影響**: セキュリティスコア向上（85→90点）
**作業時間**: 2時間
**完了日**: 2025-08-17

#### 実施内容
- [x] Alpine.js CSPビルド対応タブコンポーネント作成
- [x] 店舗詳細画面のタブ機能CSP対応
- [x] Vite開発サーバーのCSP設定追加
- [x] 本番環境反映・動作確認完了

### 1.6 本番環境セキュリティ設定反映 ✅ 完了
**現状**: 本番環境に反映済み
**作業時間**: 30分
**完了日**: 2025-08-17

#### 実施内容
- [x] git pull origin mainで全機能反映済み
- [x] セッション暗号化、CSP対策、権限管理等すべて本番稼働中
- [x] バックアップシステムも本番で自動実行中

### 1.7 バックアップシステム構築（spatie/laravel-backup）✅ 完了
**現状**: 手動バックアップのみ → 自動バックアップ実装済み
**作業時間**: 2時間
**完了日**: 2025-08-17

#### 実施内容
- [x] ZIPモジュールをDockerfileに追加
- [x] spatie/laravel-backup インストール
- [x] 設定ファイル作成・カスタマイズ
- [x] 本番環境でのセットアップ
- [x] 権限設定（daemon:daemon、775）
- [x] cronジョブ設定（毎日2時実行）
- [x] 動作確認テスト完了

#### バックアップ仕様
- **実行時間**: 毎日午前2時（自動）
- **保存先**: storage/app/backups/
- **保持期間**: 7日間（デフォルト）
- **暗号化**: パスワード保護付きZIP

### 1.8 ローカル環境での統合テスト ✅ 完了
**現状**: 各セキュリティ対策の個別実装完了後
**作業時間**: 30分
**完了日**: 2025-08-17

#### タスク詳細 ✅
- [x] セッション暗号化の動作確認（テスト3件パス）
- [x] プライベート投稿の権限確認（テスト6件パス）
- [x] CommentSection権限の動作確認（テスト7件パス）
- [x] XSS対策の動作確認（テスト5件パス）
- [x] 全体のテストスイート実行（セキュリティ関連40件中40件成功）

**注**: バックアップは本番環境専用機能のため、ローカルテスト不要

### 1.9 本番環境へのセキュリティ対策デプロイ ✅ 完了
**現状**: 全セキュリティ対策本番反映済み
**作業時間**: 1時間
**完了日**: 2025-08-17

#### 実施内容
- [x] バックアップシステム稼働確認（2回テスト成功）
- [x] git pull origin mainで全機能反映
- [x] セッション暗号化、XSS対策、権限管理すべて稼働中
- [x] cronジョブ設定済み（毎日2時実行）

### 1.10 unsafe-inline部分削除（TDD方式） ✅ 完了
**現状**: 主要部分実装完了（7ファイル修正）
**成果**: インラインスタイルの大幅削減、テスト全合格
**作業時間**: 3時間
**完了日**: 2025-08-17

#### 🔴 Red - テスト作成 ✅
- [x] tests/Feature/CspComplianceTest.php作成（RefreshDatabase削除）
- [x] 4つのテストメソッド実装（CSPヘッダー、インラインスタイル、画面表示、ドロップダウン）
  ```bash
  # テストファイル作成
  docker-compose exec app php artisan make:test CspComplianceTest
  
  # RefreshDatabase不使用確認（必須）
  docker-compose exec app grep -n "RefreshDatabase" tests/Feature/CspComplianceTest.php
  # 期待値: 何も表示されない（使用していない）
  ```
  
  **テスト内容**:
  ```php
  // 1. CSPヘッダー検証
  public function test_csp_header_does_not_contain_unsafe_inline()
  {
      $response = $this->get('/');
      $cspHeader = $response->headers->get('Content-Security-Policy-Report-Only');
      $this->assertStringNotContainsString('unsafe-inline', $cspHeader);
  }
  
  // 2. HTMLインラインスタイル検証
  public function test_no_inline_styles_in_html()
  {
      $routes = ['/', '/posts', '/users/1', '/landing'];
      foreach ($routes as $route) {
          $response = $this->get($route);
          $html = $response->getContent();
          // style属性が存在しないことを確認
          $this->assertDoesNotMatchRegularExpression('/style\s*=\s*["\']/', $html);
      }
  }
  
  // 3. 各画面の正常表示確認
  public function test_pages_render_without_csp_errors()
  {
      $this->get('/')->assertOk();
      $this->get('/posts')->assertOk();
      // 注: CSPエラーはブラウザコンソールで手動確認が必要
  }
  ```

#### 🟢 Green - 最小実装 ✅
##### 完了済み（7ファイル）
- [x] components/dropdown.blade.php - x-show化
- [x] components/dropdown-csp.blade.php - x-show化
- [x] post/partials/comment.blade.php（4箇所のインラインスタイルをTailwindクラスに変換）
- [x] profile/show.blade.php（min-heightをarbitrary valueに変換）
- [x] profile/partials/profile-card.blade.php（同上）
- [x] profile/edit.blade.php（アバター関連6箇所 + Bootstrap競合解決）
- [x] landing.blade.php（主要6箇所のセクション、フッター等）

##### 新規CSSファイル作成
- [x] resources/css/components/avatar.css（アバター関連スタイル）
- [x] resources/css/components/landing.css（ランディングページスタイル）

#### 🟡 Refactor - 品質改善 ✅
- [x] Laravel Pint実行（4ファイル自動整形）
- [x] npm run build実行（アセットビルド成功）

#### ⚪ Commit - 記録と保存 ✅
- [x] MCPメモリに実装内容記録（unsafe-inline-removal-implementation-2025-08-17）
- [x] feature/unsafe-inline-removalブランチ作成・プッシュ
- [x] GitHub PR URL: https://github.com/torahrd/portfolio/pull/new/feature/unsafe-inline-removal

### 1.11 セキュリティ対策完了評価
**現状**: Phase 1のセキュリティ対策大部分完了
**作業時間**: 30分

#### タスク詳細
- [ ] セキュリティレベルの再評価（目標: 90-95点）
- [ ] Phase 1完了の確認
- [ ] Phase 2の優先順位確認

---

## 🟡 Phase 2: UI/UX改善とリリース準備（2週間）

### 2.1 投稿作成フローの簡素化
**目標**: 3ステップ以内で投稿完了

#### タスク詳細
- [ ] 現状のフロー分析
- [ ] UI設計・ワイヤーフレーム作成
- [ ] 実装（TDD方式）
- [ ] ユーザビリティテスト

### 2.2 モバイル最適化
**現状**: 基本的なレスポンシブ対応のみ

#### タスク詳細
- [ ] モバイルでの問題点調査
- [ ] タッチ操作の最適化
- [ ] 画面サイズ別の調整
- [ ] パフォーマンス最適化

### 2.3 コメント二重投稿問題の修正【新規発見】
**現状**: 新規コメント投稿時に2個投稿される（返信時は正常）
**影響**: UX低下、データ重複
**作業時間**: 30分

#### タスク詳細
- [ ] JavaScriptイベントバインディングの確認
- [ ] フォーム送信とAjax処理の競合チェック
- [ ] 修正実装
- [ ] 動作確認

### 2.4 店舗削除ロジックの追加【新規発見】
**現状**: 投稿が0件になっても店舗情報が残る
**影響**: 空の店舗詳細ページが表示される
**作業時間**: 1時間

#### タスク詳細
- [ ] 投稿削除時に残り投稿数をチェック
- [ ] 最後の投稿削除時に店舗も削除するロジック実装
- [ ] トランザクション処理で整合性保証
- [ ] テスト作成（TDD方式）
- [ ] 本番環境での動作確認

**実装方針**:
```php
// PostController::destroy内に追加
DB::transaction(function () use ($post) {
    $shopId = $post->shop_id;
    $post->delete();
    
    // 残り投稿数を確認
    $remainingPosts = Post::where('shop_id', $shopId)->count();
    
    // 投稿が0件かつユーザー作成の店舗の場合削除
    if ($remainingPosts === 0) {
        $shop = Shop::find($shopId);
        if ($shop && $shop->created_by) {
            $shop->delete();
        }
    }
});
```

### 2.4 メンション機能の修復
**現状**: 動作していない

#### タスク詳細
- [ ] 現状の問題分析
- [ ] XSS対策を含めた実装
- [ ] テスト作成
- [ ] 動作確認

### 2.5 ランディングページ完成
**現状**: 基本実装済み、デザイン調整が必要

#### タスク詳細
- [ ] セキュリティレベルの再評価（目標: 90-95点）
- [ ] Phase 1完了の確認
- [ ] Phase 2の優先順位確認

---

## 🟡 Phase 2: UI/UX改善とリリース準備（2週間）

### 2.6 Google Mapsピン表示問題の修正 ✅ 対処済み
**現状**: ピンの代わりに画像エラーアイコンが表示 → 解決済み
**影響**: 地図の視認性低下 → 解消
**作業時間**: 20分

### 2.7 メンション機能の修復【保留】
**現状**: 動作していない（XSS対策を優先）

#### タスク詳細
- [ ] 現状の問題分析
- [ ] XSS対策を含めた実装
- [ ] テスト作成
- [ ] 動作確認

### 2.8 ランディングページデザイン改善
**現状**: 設計書完成済み、実装未着手

#### タスク詳細
- [ ] HTMLマークアップ
- [ ] スタイリング（TailwindCSS）
- [ ] インタラクション実装
- [ ] SEO最適化

---

## 🟢 Phase 3: 機能拡張（1ヶ月後〜）

### 3.1 タグ・カテゴリ機能
- [ ] データベース設計
- [ ] CRUD実装
- [ ] 検索機能との連携
- [ ] UI実装

### 3.2 エリア検索機能
- [ ] 位置情報取得
- [ ] 地理空間検索実装
- [ ] Google Maps連携強化
- [ ] UI実装

### 3.3 24店舗制限の本格実装
- [ ] 24節気マスタデータ作成
- [ ] 制限ロジック実装
- [ ] UI/UXの実装
- [ ] テスト

### 3.4 検索機能の強化
- [ ] 全文検索実装
- [ ] フィルター機能
- [ ] ソート機能
- [ ] 検索履歴

---

## ⚪ Phase 4: 収益化準備（3ヶ月後〜）

### 4.1 有料プラン機能
- [ ] 料金プラン設計
- [ ] 決済システム導入（Stripe）
- [ ] サブスクリプション管理
- [ ] 請求書発行

### 4.2 海外展開準備
- [ ] 多言語対応（i18n）
- [ ] 通貨対応
- [ ] タイムゾーン対応
- [ ] 法的要件確認

---

## 📝 完了済みタスク（最新5件）
- [x] 要件定義書（spec.md）作成
- [x] アーキテクチャ詳細（architecture.md）作成
- [x] CLAUDE.mdのLaravel仕様への修正
- [x] CSP対応状況の調査
- [x] ベストプラクティスの調査

※ 過去の完了タスクは `todo-archive.md` を参照

---

## 🚫 ブロッカー・課題
- Alpine.js CSPビルドでのx-model非対応問題
- 本番環境へのアクセス手順の確認が必要
- テスト環境の整備が不十分

---

## 📊 進捗サマリー
- **全体進捗**: 60%（MVP機能は実装済み、品質向上フェーズ）
- **セキュリティ**: 70%（基本対策済み、CSP対応中）
- **UI/UX**: 50%（基本実装済み、最適化必要）
- **収益化**: 0%（未着手）

---

## 🔧 開発環境情報
- **Laravel**: 10.48.29
- **PHP**: 8.2.28
- **MySQL**: 8.0
- **Docker**: 稼働中
- **本番URL**: https://taste-retreat.com

---

## 📚 参考資料
- [要件定義書](../spec.md)
- [アーキテクチャ詳細](../architecture.md)
- [CLAUDE.md](../claude.md)
- [LP設計書](../LP_DESIGN_INTERVIEW.md)
- [技術Q&A](docs/knowledge-base.md)

---

最終更新: 2025-08-17