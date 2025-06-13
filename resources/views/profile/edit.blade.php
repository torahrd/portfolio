@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-neutral-50 to-mocha-50 py-8">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="form-card">
            <header class="text-center mb-8">
                <h1 class="text-3xl font-bold text-neutral-900 mb-2">
                    プロフィール<span class="text-mocha-600">編集</span>
                </h1>
                <p class="text-neutral-600">あなたの情報を更新してください</p>
            </header>

            <!-- プロフィール更新フォーム -->
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profile-form">
                @csrf
                @method('patch')

                <!-- 名前 -->
                <div class="form-group">
                    <label for="name" class="form-label required">
                        <i class="fas fa-user mr-2"></i>
                        名前
                    </label>
                    <input type="text"
                        id="name"
                        name="name"
                        class="form-input-base @error('name') form-error-state @enderror"
                        value="{{ old('name', $user->name) }}"
                        required>
                    @error('name')
                    <div class="form-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- メールアドレス -->
                <div class="form-group">
                    <label for="email" class="form-label required">
                        <i class="fas fa-envelope mr-2"></i>
                        メールアドレス
                    </label>
                    <input type="email"
                        id="email"
                        name="email"
                        class="form-input-base @error('email') form-error-state @enderror"
                        value="{{ old('email', $user->email) }}"
                        required>
                    @error('email')
                    <div class="form-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- 自己紹介 -->
                <div class="form-group">
                    <label for="bio" class="form-label">
                        <i class="fas fa-comment-alt mr-2"></i>
                        自己紹介
                    </label>
                    <textarea id="bio"
                        name="bio"
                        rows="4"
                        class="form-textarea-base @error('bio') form-error-state @enderror"
                        placeholder="自己紹介を入力してください..."
                        maxlength="500">{{ old('bio', $user->bio ?? '') }}</textarea>

                    <!-- 文字カウンター -->
                    <div class="form-counter" id="bio-counter">
                        <span>
                            <span id="bio-count">0</span> / 500文字
                        </span>
                        <div class="form-counter-progress">
                            <div class="form-counter-fill" id="bio-progress"></div>
                        </div>
                    </div>

                    @error('bio')
                    <div class="form-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- 送信ボタン -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save mr-2"></i>
                        プロフィールを更新
                    </button>
                    <a href="{{ route('profile.show', $user->id) }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-times mr-2"></i>
                        キャンセル
                    </a>
                </div>
            </form>

            <!-- パスワード変更フォーム -->
            <div class="mt-12 pt-8 border-t border-neutral-200">
                <h2 class="text-xl font-bold text-neutral-900 mb-6">パスワード変更</h2>

                <form method="POST" action="{{ route('password.update') }}" id="password-form">
                    @csrf
                    @method('put')

                    <!-- 現在のパスワード -->
                    <div class="form-group">
                        <label for="current_password" class="form-label required">
                            <i class="fas fa-lock mr-2"></i>
                            現在のパスワード
                        </label>
                        <input type="password"
                            id="current_password"
                            name="current_password"
                            class="form-input-base @error('current_password', 'updatePassword') form-error-state @enderror"
                            required>
                        @error('current_password', 'updatePassword')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- 新しいパスワード -->
                    <div class="form-group">
                        <label for="password" class="form-label required">
                            <i class="fas fa-key mr-2"></i>
                            新しいパスワード
                        </label>
                        <input type="password"
                            id="password"
                            name="password"
                            class="form-input-base @error('password', 'updatePassword') form-error-state @enderror"
                            required>
                        @error('password', 'updatePassword')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- パスワード確認 -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label required">
                            <i class="fas fa-key mr-2"></i>
                            パスワード確認
                        </label>
                        <input type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-input-base"
                            required>
                    </div>

                    <!-- パスワード変更ボタン -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-shield-alt mr-2"></i>
                            パスワードを変更
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ★修正: フォーム初期化（@json使用せず） -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeProfileEdit();
    });

    function initializeProfileEdit() {
        // 文字カウンターの初期化
        initializeBioCounter();

        // フォーム検証の初期化
        initializeFormValidation();
    }

    function initializeBioCounter() {
        const bioTextarea = document.getElementById('bio');
        const bioCount = document.getElementById('bio-count');
        const bioProgress = document.getElementById('bio-progress');
        const bioCounter = document.getElementById('bio-counter');

        if (!bioTextarea || !bioCount || !bioProgress) return;

        // 初期値の設定
        updateBioCount();

        // リアルタイム更新
        bioTextarea.addEventListener('input', updateBioCount);

        function updateBioCount() {
            const currentLength = bioTextarea.value.length;
            const maxLength = 500;
            const percentage = (currentLength / maxLength) * 100;

            bioCount.textContent = currentLength;
            bioProgress.style.width = `${Math.min(percentage, 100)}%`;

            // 状態に応じてクラスを切り替え
            bioCounter.classList.remove('warning', 'error');

            if (currentLength > maxLength * 0.9) {
                bioCounter.classList.add('warning');
            }

            if (currentLength > maxLength) {
                bioCounter.classList.add('error');
            }
        }
    }

    function initializeFormValidation() {
        const profileForm = document.getElementById('profile-form');
        const passwordForm = document.getElementById('password-form');

        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                if (!validateProfileForm()) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                if (!validatePasswordForm()) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    }

    function validateProfileForm() {
        let isValid = true;

        // 名前の検証
        const name = document.getElementById('name');
        if (!name.value.trim()) {
            showFieldError(name, '名前を入力してください');
            isValid = false;
        } else {
            clearFieldError(name);
        }

        // メールアドレスの検証
        const email = document.getElementById('email');
        if (!email.value.trim()) {
            showFieldError(email, 'メールアドレスを入力してください');
            isValid = false;
        } else if (!isValidEmail(email.value)) {
            showFieldError(email, '有効なメールアドレスを入力してください');
            isValid = false;
        } else {
            clearFieldError(email);
        }

        // 自己紹介の文字数チェック
        const bio = document.getElementById('bio');
        if (bio.value.length > 500) {
            showFieldError(bio, '自己紹介は500文字以内で入力してください');
            isValid = false;
        } else {
            clearFieldError(bio);
        }

        return isValid;
    }

    function validatePasswordForm() {
        let isValid = true;

        // 現在のパスワードの検証
        const currentPassword = document.getElementById('current_password');
        if (!currentPassword.value) {
            showFieldError(currentPassword, '現在のパスワードを入力してください');
            isValid = false;
        } else {
            clearFieldError(currentPassword);
        }

        // 新しいパスワードの検証
        const newPassword = document.getElementById('password');
        if (!newPassword.value) {
            showFieldError(newPassword, '新しいパスワードを入力してください');
            isValid = false;
        } else if (newPassword.value.length < 8) {
            showFieldError(newPassword, 'パスワードは8文字以上で入力してください');
            isValid = false;
        } else {
            clearFieldError(newPassword);
        }

        // パスワード確認の検証
        const passwordConfirmation = document.getElementById('password_confirmation');
        if (!passwordConfirmation.value) {
            showFieldError(passwordConfirmation, 'パスワード確認を入力してください');
            isValid = false;
        } else if (newPassword.value !== passwordConfirmation.value) {
            showFieldError(passwordConfirmation, 'パスワードが一致しません');
            isValid = false;
        } else {
            clearFieldError(passwordConfirmation);
        }

        return isValid;
    }

    function showFieldError(field, message) {
        clearFieldError(field);

        field.classList.add('form-error-state');

        const errorDiv = document.createElement('div');
        errorDiv.className = 'form-error mt-2';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;

        field.parentNode.appendChild(errorDiv);
    }

    function clearFieldError(field) {
        field.classList.remove('form-error-state');

        const existingError = field.parentNode.querySelector('.form-error');
        if (existingError && !existingError.hasAttribute('data-server-error')) {
            existingError.remove();
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
</script>
@endsection