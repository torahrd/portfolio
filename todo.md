# タスク管理

## 現在のタスク

### 🔴 緊急タスク
- [ ] なし

### 🟡 重要タスク
- [ ] なし

### 🟢 通常タスク
- [ ] **Phase 12: 最終確認とクリーンアップ（一時停止中）**
- [ ] **Phase 13: 店舗検索機能の実装（完了）**

## 完了済みタスク（最新）

### Phase 11: JavaScriptの簡略化（完了）
- [x] Alpine.jsロジックの外部JS化・@verbatim整理（主要コンポーネント全般）

### Phase 8: Moleculesコンポーネントのリファクタリング
- [x] 複数Moleculesコンポーネントのリファクタリング

### 過去の完了タスク
詳細は `src/todo-archive.md` を参照してください。
- **Phase 1-7, 9, 10**: プロジェクト構造分析、Atomsリファクタリング、コメント非同期化、ルール最適化など

### Phase 14: 画像アップロード機能のCloudinary移行・整理
- [x] Cloudinary単一画像アップロード仕様でリリース。詳細はアーカイブ参照。

## 問題のあるタスク

- [!] なし

## 今後の予定タスク

### Phase 12: 最終確認とクリーンアップ（一時停止中）
<!-- このセクションはアーカイブに移動します -->

### Phase 13: 店舗検索機能の実装（一時停止中）
- [ ] 検索機能の不具合調査・修正
  - [ ] サジェストバーが正常動作しない原因の特定
  - [ ] 必要に応じてAPI・Blade・JSの修正

### 今後のアップデート方針（画像アップロード機能）
- 複数画像アップロード（サブ画像ギャラリー、ドラッグ＆ドロップ、並び替え等）は将来的な拡張候補。
- 現状は「1枚のみアップロード」でリリースし、リッチなUI/UXはリリース後の段階的アップデートで対応。

## 次フェーズ以降のタスク
- [ ] ユーザー削除ボタンが表示されない問題の調査・対応
  - [ ] プロフィール編集画面で「アカウント削除」ボタンが表示されない原因を調査し、必要に応じてBlade/認可/ルーティング等を修正

## メモ
- コメント削除のダイアログは標準confirm()のため、カスタムモーダル化しない限りautofocus制御不可
- UIアクセシビリティ改善は段階的に進める

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

## 今後の予定

### 日次更新
- 進捗状況の確認
- ブロッカーの特定
- 次のアクションの決定

### 週次レビュー
- 完了タスクの確認
- 未完了タスクの再評価
- 新規タスクの追加

### 月次振り返り
- パフォーマンスの評価
- 改善点の特定
- 次のマイルストーンの設定

## メモ

- ルールファイルが正常に作成されました
- タスク管理システムが稼働開始しました
- 環境セッティングが完了し、開発準備が整いました
- アプリケーションは http://localhost でアクセス可能です
- **現在の優先タスク**: Phase 9 UIの微調整
- **一時停止中のタスク**: Phase 10以降のリファクタリング作業
- リファクタリングの基本原則：1コンポーネント1機能、拡張性より単純性、可読性最優先、保守性重視、Tailwindクラス直接記述
- **新しいルール体系**: 8つのルールファイルで包括的な開発ガイドラインを構築
- **ルール見落とし防止**: 作業開始前に必ず全てのルールファイルを参照し、適用状況を確認

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
  - `comment-card.blade.php`（size="default"）
  - `post/show.blade.php`（size="lg"）

### Moleculesコンポーネント
- **post-actions.blade.php**: 投稿カードのアクション（いいね・コメント）をコンポーネント化
- **visit-status-badge.blade.php**: 訪問ステータスバッジをコンポーネント化
- **統合完了**: 重複コードの削除と再利用性の向上 

