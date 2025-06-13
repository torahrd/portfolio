<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-neutral-800 leading-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white">
                    <i class="fas fa-user-edit text-lg"></i>
                </div>
                プロフィール設定
            </h2>

            <a href="{{ route('profile.show', auth()->user()) }}"
                class="btn btn-outline-secondary hover-lift">
                <i class="fas fa-eye mr-2"></i>
                プロフィールを見る
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- タブナビゲーション -->
        <div class="glass-card p-2 mb-8 animate-slide-down">
            <div class="neu-tabs">
                <button class="neu-tab active" data-tab="profile">
                    <i class="fas fa-user mr-2"></i>
                    基本情報
                </button>
                <button class="neu-tab" data-tab="account">
                    <i class="fas fa-cog mr-2"></i>
                    アカウント設定
                </button>
                <button class="neu-tab" data-tab="privacy">
                    <i class="fas fa-shield-alt mr-2"></i>
                    プライバシー
                </button>
                <button class="neu-tab" data-tab="danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    危険な操作
                </button>
            </div>
        </div>

        <!-- 基本情報タブ -->
        <div id="profile-tab" class="tab-content animate-fade-in">
            <div class="glass-card p-8">
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gradient-mocha mb-2 flex items-center gap-3">
                        <i class="fas fa-id-card text-mocha-500"></i>
                        基本情報
                    </h3>
                    <p class="text-neutral-600 leading-relaxed">
                        プロフィール情報を更新して、他のユーザーにあなたのことを知ってもらいましょう。
                    </p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}"
                    enctype="multipart/form-data"
                    x-data="profileForm()"
                    @submit.prevent="submitForm">
                    @csrf
                    @method('patch')

                    <!-- プロフィール画像 -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-camera mr-2"></i>
                            プロフィール画像
                        </label>

                        <div class="flex items-start gap-6">
                            <!-- 現在の画像 -->
                            <div class="relative">
                                <img src="{{ auth()->user()->avatar_url }}"
                                    alt="プロフィール画像"
                                    class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg"
                                    id="avatar-preview">

                                <!-- 画像変更オーバーレイ -->
                                <div class="absolute inset-0 bg-black/50 rounded-2xl flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300 cursor-pointer"
                                    onclick="document.getElementById('avatar-input').click()">
                                    <i class="fas fa-camera text-white text-2xl"></i>
                                </div>
                            </div>

                            <!-- アップロードコントロール -->
                            <div class="flex-1">
                                <input type="file"
                                    id="avatar-input"
                                    name="avatar"
                                    accept="image/*"
                                    class="form-file-input"
                                    @change="previewImage">

                                <label for="avatar-input"
                                    class="form-file-label cursor-pointer min-h-32">
                                    <i class="form-file-icon fas fa-cloud-upload-alt"></i>
                                    <div class="form-file-text">
                                        <div class="form-file-primary">画像をアップロード</div>
                                        <div class="form-file-secondary">
                                            JPG, PNG, GIF (最大 2MB)
                                        </div>
                                    </div>
                                </label>

                                @error('avatar')
                                <div class="form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 名前 -->
                    <div class="form-group">
                        <label for="name" class="form-label required">
                            <i class="fas fa-user mr-2"></i>
                            表示名
                        </label>
                        <input type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', auth()->user()->name) }}"
                            class="form-input-base @error('name') form-error-state @enderror"
                            placeholder="あなたの表示名を入力"
                            required>
                        @error('name')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror
                        <div class="form-help-text">
                            <i class="fas fa-info-circle mr-1"></i>
                            他のユーザーに表示される名前です
                        </div>
                    </div>

                    <!-- 自己紹介 -->
                    <div class="form-group">
                        <label for="bio" class="form-label">
                            <i class="fas fa-quote-left mr-2"></i>
                            自己紹介
                        </label>
                        <textarea id="bio"
                            name="bio"
                            rows="4"
                            class="form-textarea-base @error('bio') form-error-state @enderror"
                            placeholder="あなたの食べ物の好みや、どんなお店が好きかを教えてください..."
                            x-model="bio"
                            @input="updateBioCount">{{ old('bio', auth()->user()->bio) }}</textarea>

                        <!-- 文字数カウンター -->
                        <div class="form-counter" :class="{ 'warning': bioCount > 180, 'error': bioCount > 200 }">
                            <span>
                                <span x-text="bioCount"></span> / 200文字
                            </span>
                            <div class="form-counter-progress">
                                <div class="form-counter-fill" :style="`width: ${Math.min(bioCount / 200 * 100, 100)}%`"></div>
                            </div>
                        </div>

                        @error('bio')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- 保存ボタン -->
                    <div class="form-actions">
                        <button type="submit"
                            class="btn btn-primary"
                            :disabled="isSubmitting">
                            <template x-if="isSubmitting">
                                <i class="fas fa-spinner animate-spin mr-2"></i>
                            </template>
                            <template x-if="!isSubmitting">
                                <i class="fas fa-save mr-2"></i>
                            </template>
                            プロフィールを更新
                        </button>

                        <a href="{{ route('profile.show', auth()->user()) }}"
                            class="btn btn-outline-secondary">
                            <i class="fas fa-times mr-2"></i>
                            キャンセル
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- アカウント設定タブ -->
        <div id="account-tab" class="tab-content hidden">
            <div class="space-y-6">
                <!-- メールアドレス変更 -->
                <div class="glass-card p-8">
                    <h3 class="text-xl font-bold text-neutral-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-envelope text-electric-500"></i>
                        メールアドレス
                    </h3>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="form-group">
                            <label for="email" class="form-label required">現在のメールアドレス</label>
                            <input type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                class="form-input-base @error('email') form-error-state @enderror"
                                required>
                            @error('email')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                メールアドレスを更新
                            </button>
                        </div>
                    </form>
                </div>

                <!-- パスワード変更 -->
                <div class="glass-card p-8">
                    <h3 class="text-xl font-bold text-neutral-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-lock text-warning-500"></i>
                        パスワード変更
                    </h3>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="current_password" class="form-label required">現在のパスワード</label>
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

                        <div class="form-group">
                            <label for="password" class="form-label required">新しいパスワード</label>
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

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label required">新しいパスワード（確認）</label>
                            <input type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-input-base @error('password_confirmation', 'updatePassword') form-error-state @enderror"
                                required>
                            @error('password_confirmation', 'updatePassword')
                            <div class="form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                パスワードを更新
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- プライバシー設定タブ -->
        <div id="privacy-tab" class="tab-content hidden">
            <div class="glass-card p-8">
                <h3 class="text-xl font-bold text-neutral-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-sage-500"></i>
                    プライバシー設定
                </h3>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <!-- アカウントの公開設定 -->
                    <div class="form-group">
                        <label class="form-label">アカウントの公開設定</label>
                        <div class="space-y-4">
                            <div class="form-radio-item">
                                <input type="radio"
                                    id="public_account"
                                    name="is_private"
                                    value="0"
                                    {{ !auth()->user()->is_private ? 'checked' : '' }}
                                    class="form-radio">
                                <label for="public_account" class="flex items-center gap-3 cursor-pointer">
                                    <div>
                                        <div class="font-semibold text-success-700">
                                            <i class="fas fa-globe mr-2"></i>
                                            パブリックアカウント
                                        </div>
                                        <div class="text-sm text-neutral-600">
                                            誰でもあなたのプロフィールと投稿を見ることができます
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="form-radio-item">
                                <input type="radio"
                                    id="private_account"
                                    name="is_private"
                                    value="1"
                                    {{ auth()->user()->is_private ? 'checked' : '' }}
                                    class="form-radio">
                                <label for="private_account" class="flex items-center gap-3 cursor-pointer">
                                    <div>
                                        <div class="font-semibold text-warning-700">
                                            <i class="fas fa-lock mr-2"></i>
                                            プライベートアカウント
                                        </div>
                                        <div class="text-sm text-neutral-600">
                                            フォロワーのみがあなたの投稿を見ることができます
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            プライバシー設定を更新
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 危険な操作タブ -->
        <div id="danger-tab" class="tab-content hidden">
            <div class="glass-card p-8 border-error-200">
                <h3 class="text-xl font-bold text-error-700 mb-6 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-error-500"></i>
                    危険な操作
                </h3>

                <div class="bg-error-50 border border-error-200 rounded-xl p-6">
                    <h4 class="font-semibold text-error-800 mb-3">アカウントを削除</h4>
                    <p class="text-error-700 text-sm leading-relaxed mb-4">
                        アカウントを削除すると、すべての投稿、フォロー関係、その他のデータが完全に削除されます。
                        この操作は取り消すことができません。
                    </p>

                    <form method="post" action="{{ route('profile.destroy') }}"
                        x-data="{ showDeleteModal: false, password: '' }">
                        @csrf
                        @method('delete')

                        <button type="button"
                            @click="showDeleteModal = true"
                            class="btn bg-error-600 text-white hover:bg-error-700 border-error-600">
                            <i class="fas fa-trash mr-2"></i>
                            アカウントを削除
                        </button>

                        <!-- 削除確認モーダル -->
                        <div x-show="showDeleteModal"
                            class="glass-modal-backdrop"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100">

                            <div class="glass-modal">
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 rounded-full bg-error-100 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-exclamation-triangle text-error-500 text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-neutral-900 mb-2">アカウントを削除しますか？</h3>
                                    <p class="text-neutral-600 text-sm">
                                        この操作は取り消すことができません。<br>
                                        パスワードを入力して確認してください。
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label for="delete_password" class="form-label required">パスワード</label>
                                    <input type="password"
                                        id="delete_password"
                                        name="password"
                                        x-model="password"
                                        class="form-input-base"
                                        placeholder="パスワードを入力"
                                        required>
                                    @error('password', 'userDeletion')
                                    <div class="form-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-end gap-3">
                                    <button type="button"
                                        @click="showDeleteModal = false; password = ''"
                                        class="btn btn-outline-secondary">
                                        キャンセル
                                    </button>
                                    <button type="submit"
                                        :disabled="!password"
                                        class="btn bg-error-600 text-white hover:bg-error-700 border-error-600">
                                        <i class="fas fa-trash mr-2"></i>
                                        削除する
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Script -->
    <script>
        function profileForm() {
            return {
                bio: @json(old('bio', auth() - > user() - > bio ?? '')),
                bioCount: 0,
                isSubmitting: false,

                init() {
                    this.updateBioCount();
                },

                updateBioCount() {
                    this.bioCount = this.bio ? this.bio.length : 0;
                },

                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            document.getElementById('avatar-preview').src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                async submitForm(event) {
                    this.isSubmitting = true;

                    try {
                        // フォームを通常通り送信
                        event.target.submit();
                    } catch (error) {
                        this.isSubmitting = false;
                        console.error('Form submission error:', error);
                    }
                }
            }
        }

        // タブ切り替え機能
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.neu-tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;

                    // アクティブ状態の更新
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // コンテンツの表示/非表示
                    tabContents.forEach(content => {
                        if (content.id === targetTab + '-tab') {
                            content.classList.remove('hidden');
                            content.classList.add('animate-fade-in');
                        } else {
                            content.classList.add('hidden');
                            content.classList.remove('animate-fade-in');
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>