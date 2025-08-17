<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>プロフィール編集</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- アプリケーションCSS -->
    @vite(['resources/css/app.css'])
    <!-- カスタムCSS -->
    <style>
        label[for="avatar"]:hover {
            opacity: 1 !important;
        }
        /* Bootstrap position-absoluteクラスを上書き */
        .avatar-camera-btn {
            position: absolute !important;
            bottom: 10px !important;
            right: 10px !important;
            background: #fff !important;
            width: 36px !important;
            height: 36px !important;
            border-radius: 50% !important;
            padding: 0 !important;
            border: 1px solid rgba(13,110,253,0.5) !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
            cursor: pointer !important;
            opacity: 0.85 !important;
            transition: opacity 0.2s !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .avatar-container {
            width: 128px !important;
            height: 128px !important;
            position: relative !important;
            display: inline-block !important;
        }
        .avatar-image {
            width: 128px !important;
            height: 128px !important;
            object-fit: cover !important;
            background: #f3f4f6 !important;
            border: 1px solid rgba(108,117,125,0.4) !important;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>プロフィール編集</h2>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}"
                            enctype="multipart/form-data" id="profileForm">
                            @csrf
                            @method('PATCH')

                            <!-- プロフィール画像 -->
                            <div class="mb-4 text-center">
                                <div class="avatar-container mx-auto">
                                    @if($user->avatar)
                                    <img id="profilePreview" src="{{ $user->avatar }}"
                                        alt="プロフィール画像"
                                        class="rounded-circle shadow-sm avatar-image" />
                                    @else
                                    <div id="profilePreview" class="rounded-circle d-flex align-items-center justify-content-center shadow-sm bg-light text-secondary avatar-image text-5xl">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    @endif
                                    <label for="avatar" class="avatar-camera-btn">
                                        <i class="fas fa-camera text-primary avatar-camera-icon"></i>
                                        <span class="visually-hidden">画像を変更</span>
                                    </label>
                                    <input type="file" id="avatar" name="avatar" accept="image/*" class="avatar-file-input">
                                </div>
                                @error('avatar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 名前 -->
                            <div class="mb-3">
                                <label for="name" class="form-label">名前 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}"
                                    required maxlength="50">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 自己紹介 -->
                            <div class="mb-3">
                                <label for="bio" class="form-label">自己紹介</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror"
                                    id="bio" name="bio" rows="4" maxlength="500"
                                    placeholder="自己紹介を入力してください">{{ old('bio', $user->bio) }}</textarea>
                                <small class="form-text text-muted">
                                    <span id="bioCount">{{ strlen($user->bio ?? '') }}</span>/500文字
                                </small>
                                @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 場所 -->
                            <div class="mb-3">
                                <label for="location" class="form-label">場所</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location"
                                    value="{{ old('location', $user->location) }}" maxlength="100"
                                    placeholder="例: 東京都渋谷区">
                                @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ウェブサイト -->
                            <div class="mb-3">
                                <label for="website" class="form-label">ウェブサイト</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                    id="website" name="website"
                                    value="{{ old('website', $user->website) }}"
                                    placeholder="https://example.com">
                                @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- プライバシー設定 -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_private"
                                        name="is_private" value="1"
                                        {{ old('is_private', $user->is_private) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_private">
                                        <strong>プライベートアカウント</strong>
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    プライベートアカウントにすると、フォローを承認したユーザーのみがあなたの投稿を見ることができます。
                                </small>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('profile.show', $user) }}" class="btn btn-secondary" autofocus>
                                    <i class="fas fa-arrow-left"></i> キャンセル
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> 保存
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // 文字数カウント
            $('#bio').on('input', function() {
                const count = $(this).val().length;
                $('#bioCount').text(count);

                if (count > 500) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // 画像プレビュー
            $('#avatar').change(function(e) {
                const file = e.target.files[0];
                if (file) {
                    // ファイルサイズチェック
                    if (file.size > 4 * 1024 * 1024) {
                        alert('ファイルサイズは4MB以下にしてください');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // imgタグがあればsrcを書き換え、なければimgタグを生成して差し替え
                        let $preview = $('#profilePreview');
                        if ($preview.is('img')) {
                            $preview.attr('src', e.target.result);
                        } else {
                            // divをimgに差し替え
                            $preview.replaceWith('<img id="profilePreview" src="' + e.target.result + '" alt="プロフィール画像" class="rounded-circle border border-2 border-secondary shadow-sm avatar-image" />');
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>

</html>