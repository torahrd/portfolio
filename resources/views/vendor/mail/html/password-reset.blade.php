<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>パスワードリセットのお知らせ</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .button { display: inline-block; background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
        .warning { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>パスワードリセットのお知らせ</h1>
        </div>
        
        <div class="content">
            <p>アカウントのパスワードリセットリクエストを受け付けました。</p>
            
            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="button">パスワードをリセット</a>
            </div>
            
            <div class="warning">
                <p><strong>このパスワードリセットリンクは60分後に期限切れになります。</strong></p>
            </div>
            
            <p>パスワードリセットをリクエストしていない場合は、このメールを無視してください。追加の操作は必要ありません。</p>
            
            <p>よろしくお願いいたします。<br>
            {{ config('app.name') }}</p>
        </div>
        
        <div class="footer">
            <p>「パスワードをリセット」ボタンがクリックできない場合は、以下のURLをコピーしてブラウザに貼り付けてください：</p>
            <p style="word-break: break-all; font-size: 11px; color: #007bff;">{{ $actionUrl }}</p>
        </div>
    </div>
</body>
</html> 