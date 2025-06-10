{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>プロフィール編集</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}"
                        enctype="multipart/form-data" id="profileForm">
                        @csrf
                        @method('PATCH')

                        {{-- プロフィール画像 --}}
                        <div class="form-group text-center mb-4">
                            <div class="profile-image-container">
                                <img id="profilePreview" src="{{ $user->avatar_url }}"
                                    alt="プロフィール画像" class="profile-image-edit">
                                <div class="image-overlay">
                                    <label for="avatar" class="image-upload-btn">
                                        <i class="fas fa-camera"></i>
                                        <span>画像を変更</span>
                                    </label>
                                    <input type="file" id="avatar" name="avatar"
                                        accept="image/*" style="display: none;">
                                </div>
                            </div>
                            @error('avatar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 名前 --}}
                        <div class="form-group">
                            <label for="name">名前 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $user->name) }}"
                                required maxlength="50">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 自己紹介 --}}
                        <div class="form-group">
                            <label for="bio">自己紹介</label>
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

                        {{-- 場所 --}}
                        <div class="form-group">
                            <label for="location">場所</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                id="location" name="location"
                                value="{{ old('location', $user->location) }}" maxlength="100">
                            @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ウェブサイト --}}
                        <div class="form-group">
                            <label for="website">ウェブサイト</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror"
                                id="website" name="website"
                                value="{{ old('website', $user->website) }}"
                                placeholder="https://example.com">
                            @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- プライバシー設定 --}}
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_private"
                                    name="is_private" value="1"
                                    {{ old('is_private', $user->is_private) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_private">
                                    プライベートアカウント
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                プライベートアカウントにすると、フォローを承認したユーザーのみがあなたの投稿を見ることができます。
                            </small>
                        </div>

                        <div class="form-group text-right">
                            <a href="{{ route('profile.show', $user) }}" class="btn btn-secondary mr-2">
                                キャンセル
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
@endsection

@push('scripts')
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
                if (file.size > 2 * 1024 * 1024) {
                    alert('ファイルサイズは2MB以下にしてください');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#profilePreview').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush