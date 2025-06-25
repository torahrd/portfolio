# タスク管理

## 現在のタスク

### 🔴 緊急タスク
- [ ] なし

### 🟡 重要タスク
- [ ] なし

### 🟢 通常タスク
- [ ] **Phase 15: Google Maps API連携実装（新規追加）**
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

## Phase 15: Google Maps API連携実装（詳細計画）

### Phase 15-1: 基盤構築（1-2週間）

#### 1.1 環境設定とAPI準備
- [x] Google Cloud ConsoleでのAPIキー取得
  - [x] Maps JavaScript API有効化
  - [x] Places API有効化
  - [x] Geocoding API有効化
- [x] 環境変数設定（フロントエンド用・バックエンド用分離）
  - [x] `.env`ファイルにAPIキー追加
  - [x] 本番環境での制限設定
- [x] 必要なパッケージインストール
  - [x] `skagarwal/google-places-api`（v3.0+）

#### 1.2 データベース設計
- [x] 既存の`latitude`、`longitude`フィールドの活用確認
- [x] Google Places API用の追加フィールド設計
  - [x] `google_place_id`（ユニーク制約）
  - [x] `formatted_phone_number`（Google Places APIから取得した電話番号）
  - [x] `website`（Google Places APIから取得した公式サイトURL）
  - [x] 既存の`address`フィールドを活用（正規化された住所）
  - [x] 既存の`business_hours`テーブルを活用（営業時間）
- [x] インデックス設計
  - [x] `google_place_id`のインデックス
  - [x] `latitude`、`longitude`の複合インデックス

#### 1.3 サービス層実装
- [x] `GooglePlacesService`クラス作成
  - [x] 多段階キャッシュ戦略実装（Redis + DB）
  - [x] エラーハンドリング・回路ブレーカーパターン
  - [x] レート制限対応
- [x] `TextNormalizationService`クラス作成
  - [x] 店舗名の正規化機能
  - [x] 住所の正規化機能
  - [x] 多言語対応（ひらがな・カタカナ・漢字・ローマ字）

#### 1.4 モデル層実装
- [x] Shopモデルの更新
  - [x] Google Places API関連フィールドの追加
  - [x] スコープメソッドの実装
  - [x] API連携メソッドの実装
  - [x] 営業時間更新メソッドの実装

#### 1.5 評価機能の削除（個人の好み重視のため）
- [x] 評価関連ファイルの削除
  - [x] `rating.blade.php`コンポーネントの削除
  - [x] `shop-info-card.blade.php`から評価表示部分の削除
  - [x] `GooglePlacesService.php`から評価関連フィールドの削除

### Phase 15-2: 情報取得機能実装（2-3週間）

#### 2.1 店舗検索・情報取得
- [ ] 投稿作成画面での店舗名検索機能
  - [ ] リアルタイム検索（Autocomplete）
  - [ ] 検索結果の表示・選択機能
- [ ] Google Places APIからの情報自動取得
  - [ ] 店舗詳細情報の取得
  - [ ] 座標情報の取得
  - [ ] 営業時間情報の取得
- [ ] 取得データの自動入力機能
  - [ ] フォームへの自動入力
  - [ ] ユーザー確認機能

#### 2.2 重複対策実装
- [ ] Google Place IDによる重複チェック
- [ ] 正規化された名前・住所による類似店舗検出
- [ ] 重複時の処理ロジック
  - [ ] 既存店舗の選択案内
  - [ ] 新規作成の確認
- [ ] データ整合性の確保
  - [ ] 月次更新機能の実装
  - [ ] 手動更新機能の実装

### Phase 15-3: マッピング機能実装（2-3週間）

#### 3.1 地図表示基盤
- [ ] 専用地図ページの作成
  - [ ] レスポンシブ対応
  - [ ] モバイル最適化
- [ ] Google Maps JavaScript API統合
  - [ ] 最新のAdvancedMarkerElement実装
  - [ ] マップID設定
  - [ ] 地図スタイルのカスタマイズ

#### 3.2 マーカー表示
- [ ] 店舗マーカーの表示
  - [ ] カスタムマーカーアイコン
  - [ ] マーカークラスタリング
  - [ ] ビューポート内レンダリング最適化
- [ ] インタラクティブ機能
  - [ ] クリック時の店舗詳細画面遷移
  - [ ] ホバー時の情報表示
  - [ ] ズーム・パン機能

### Phase 15-4: 最適化・セキュリティ（1-2週間）

#### 4.1 パフォーマンス最適化
- [ ] キャッシュ戦略の実装
  - [ ] Redisキャッシュ（30分）
  - [ ] データベースキャッシュ（24時間）
  - [ ] ブラウザキャッシュ（1時間）
- [ ] 画像最適化（Cloudinary連携）
  - [ ] マーカー画像の最適化
  - [ ] 店舗画像の最適化
- [ ] レンダリング最適化
  - [ ] 遅延読み込み
  - [ ] 仮想スクロール

#### 4.2 セキュリティ強化
- [ ] APIキー制限設定
  - [ ] HTTPリファラー制限
  - [ ] IPアドレス制限
- [ ] レート制限実装
  - [ ] アプリケーションレベル制限
  - [ ] ユーザー別制限
- [ ] 監視システム導入
  - [ ] 使用量監視
  - [ ] エラー監視
  - [ ] コスト監視

### Phase 15-5: 海外対応準備（将来的な拡張）

#### 5.1 多言語対応基盤
- [ ] データベース設計の拡張
  - [ ] 多言語店舗名フィールド
  - [ ] 多言語住所フィールド
  - [ ] 国コード・言語コードフィールド
- [ ] 多言語UI対応
  - [ ] Laravelローカライゼーション
  - [ ] 言語切り替え機能
  - [ ] 右から左（RTL）対応

#### 5.2 旅行者向け機能
- [ ] 通貨変換機能
- [ ] 時差対応
- [ ] 文化的配慮機能
- [ ] 多言語検索機能

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
- **現在の優先タスク**: Phase 15 Google Maps API連携実装
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

- [x] プロフィール画像が表示されない・UI崩れの修正
  - DBのカラム名（avatar_url→avatar）不一致を修正し、Bladeファイル全体でavatar参照に統一
  - プロフィール詳細画面の画像表示が崩れる問題をインラインスタイル追加で修正
  - 他画面も画像表示・UI問題なしを確認
  - 動作確認済み（2025/06/25）
- [~] Nginxの413エラー（Request Entity Too Large）で2MB超の画像アップロード不可
  - default.confでclient_max_body_size 4M;を設定済みだが、Docker環境で反映されない問題が継続中
  - php.iniのupload_max_filesize, post_max_sizeは十分大きい（64M/128M）
  - 1MB以下の画像はアップロード可能
  - **調査結果**: Docker+nginx環境ではdefault.confのマウントやCOPYが正しく反映されているか、nginxコンテナ内の/etc/nginx/conf.d/default.confを必ず確認する必要あり
  - 反映されない場合は、ボリュームマウントやDockerfileのCOPYのパス・順序・キャッシュクリア（--no-cache）を再確認
  - nginx再起動（reload/restart）も必須
  - それでも反映されない場合、nginxイメージのビルドやdocker-compose.ymlのvolumes設定を見直す
  - 参考: [Qiita記事1](https://qiita.com/mejileben/items/5a4702d897793efc9aae) [Qiita記事2](https://qiita.com/mumucochimu/items/d380fafbe8445d3459dd)
  - **今後の対応案**:
    - 1. nginxコンテナ内で/etc/nginx/conf.d/default.confの内容を直接確認し、client_max_body_sizeが反映されているか検証
    - 2. 必要に応じてdocker-compose.ymlのvolumes設定やDockerfileのCOPY順序を修正
    - 3. 反映後はdocker compose down/up --buildで完全再起動
    - 4. それでも不可ならnginxイメージのキャッシュクリアや別イメージ検証も検討
  - 投稿作成画面・プロフィール編集画面ともに同様の現象を確認
  - **現状は1MB以下で運用、4MB以上対応は継続調査・今後の課題**

- [ ] 4MB以上の画像アップロード対応（Nginx設定反映問題の恒久対応）
  - 上記調査・修正を段階的に実施
  - 完全対応後は動作確認・ドキュメント反映

## 今後の予定
- Nginxのclient_max_body_size本番対応・4MB超の画像アップロード対応
- 本番環境用のNginx設定管理・CI/CD連携

