# Git ワークフロー

## GitHubへのプッシュ手順

### 重要: SSHキーについて
SSHキーはDockerコンテナ内に格納されているため、GitHubへのプッシュはDockerコンテナ経由で実行する必要があります。

### 基本的なプッシュ手順

```bash
# 1. 変更をステージング
git add .

# 2. コミット
git commit -m "コミットメッセージ"

# 3. GitHubへプッシュ（Dockerコンテナ経由）
docker compose exec app git push origin ブランチ名
```

### トラブルシューティング

#### リモートとの競合が発生した場合
```bash
# リモートの変更を取り込む
docker compose exec app git pull origin ブランチ名 --rebase

# 再度プッシュ
docker compose exec app git push origin ブランチ名
```

#### Gitオブジェクトエラーが発生した場合
```bash
# リモートの不要なリファレンスを削除
docker compose exec app git remote prune origin

# 強制プッシュ（注意: チーム開発では事前確認必須）
docker compose exec app git push origin ブランチ名 -f
```

## ブランチ戦略

- `main`: 本番環境
- `develop`: 開発環境
- `feature/*`: 機能開発
- `hotfix/*`: 緊急修正

## コミットメッセージ規約

```
<type>: <subject>

<body>

<footer>
```

### Type
- `feat`: 新機能
- `fix`: バグ修正
- `docs`: ドキュメント
- `style`: コードスタイル
- `refactor`: リファクタリング
- `test`: テスト
- `chore`: ビルド・補助ツール

### 例
```
feat: コメント機能のCSP対応

- Alpine.js CSPビルド制限への対応
- 非同期処理の実装
- アニメーション追加
```

## デプロイフロー

1. **開発完了** → feature/ブランチでコミット
2. **GitHubプッシュ** → `docker compose exec app git push`
3. **PR作成** → GitHub上でPull Request
4. **マージ** → developまたはmainへマージ
5. **本番デプロイ** → 本番サーバーでpull & build

## 本番環境への反映

```bash
# 本番サーバーにSSH接続後
cd /var/www/taste-retreat
git pull origin main
composer install --no-dev
npm run build
php artisan migrate
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```