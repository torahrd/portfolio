<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            プロフィール編集
        </h2>
    </x-slot>

    <div class="container-responsive py-6">
        <div class="max-w-2xl mx-auto">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-2xl font-semibold">プロフィール編集</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}"
                        enctype="multipart/form-data"
                        id="profileForm"
                        x-data="{ 
                              avatarPreview: '{{ $user->avatar_url }}',
                              submitting: false 
                          }">
                        @csrf
                        @method('PATCH')

                        <!-- プロフィール画像 -->
                        <div class="form-group text-center">
                            <div class="profile-image-container">
                                <img :src="avatarPreview"
                                    alt="プロフィール画像"
                                    class="profile-image-edit">
                                <div class="image-overlay">
                                    <label for="avatar" class="image-upload-btn cursor-pointer">
                                        <i class="fas fa-camera text-xl"></i>
                                        <span class="block text-xs mt-1">画像を変更</span>
                                    </label>
                                    <input type="file"
                                        id="avatar"
                                        name="avatar"
                                        accept="image/*"
                                        class="hidden"
                                        @change="previewAvatar($event)">
                                </div>
                            </div>
                            @error('avatar')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 名前 -->
                        <div class="form-group">
                            <label for="name">名前</label>
                            <input type="text"
                                id="name"
                                name="name"
                                class="form-input @error('name') border-red-500 @enderror"
                                value="{{ old('name', $user->name) }}"
                                required>
                            @error('name')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- メールアドレス -->
                        <div class="form-group">
                            <label for="email">メールアドレス</label>
                            <input type="email"
                                id="email"
                                name="email"
                                class="form-input @error('email') border-red-500 @enderror"
                                value="{{ old('email', $user->email) }}"
                                required>
                            @error('email')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 自己紹介 -->
                        <div class="form-group">
                            <label for="bio">自己紹介</label>
                            <textarea id="bio"
                                name="bio"
                                rows="4"
                                class="form-textarea @error('bio') border-red-500 @enderror"
                                placeholder="あなたについて教えてください...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 所在地 -->
                        <div class="form-group">
                            <label for="location">所在地</label>
                            <input type="text"
                                id="location"
                                name="location"
                                class="form-input @error('location') border-red-500 @enderror"
                                value="{{ old('location', $user->location) }}"
                                placeholder="例: 東京都渋谷区">
                            @error('location')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ウェブサイト -->
                        <div class="form-group">
                            <label for="website">ウェブサイト</label>
                            <input type="url"
                                id="website"
                                name="website"
                                class="form-input @error('website') border-red-500 @enderror"
                                value="{{ old('website', $user->website) }}"
                                placeholder="https://example.com">
                            @error('website')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- プライベートアカウント設定 -->
                        <div class="form-group">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox"
                                    id="is_private"
                                    name="is_private"
                                    value="1"
                                    class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500"
                                    {{ old('is_private', $user->is_private) ? 'checked' : '' }}>
                                <label for="is_private" class="text-sm font-medium text-gray-700">
                                    プライベートアカウント
                                </label>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                有効にすると、承認されたフォロワーのみがあなたの投稿を見ることができます。
                            </p>
                            @error('is_private')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 送信ボタン -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('profile.show', $user) }}" class="btn btn-secondary">
                                キャンセル
                            </a>
                            <button type="submit"
                                class="btn btn-primary"
                                :disabled="submitting"
                                @click="submitting = true">
                                <span x-show="!submitting">保存する</span>
                                <span x-show="submitting" class="flex items-center">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>保存中...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- パスワード変更セクション -->
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">パスワード変更</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}"
                        x-data="{ submitting: false }">
                        @csrf
                        @method('PUT')

                        <!-- 現在のパスワード -->
                        <div class="form-group">
                            <label for="current_password">現在のパスワード</label>
                            <input type="password"
                                id="current_password"
                                name="current_password"
                                class="form-input @error('current_password') border-red-500 @enderror"
                                required>
                            @error('current_password')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 新しいパスワード -->
                        <div class="form-group">
                            <label for="password">新しいパスワード</label>
                            <input type="password"
                                id="password"
                                name="password"
                                class="form-input @error('password') border-red-500 @enderror"
                                required>
                            @error('password')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- パスワード確認 -->
                        <div class="form-group">
                            <label for="password_confirmation">パスワード確認</label>
                            <input type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-input"
                                required>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="btn btn-primary"
                                :disabled="submitting"
                                @click="submitting = true">
                                <span x-show="!submitting">パスワードを変更</span>
                                <span x-show="submitting" class="flex items-center">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>変更中...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- アカウント削除セクション -->
            <div class="card mt-6 border-red-200">
                <div class="card-header bg-red-50">
                    <h3 class="text-lg font-semibold text-red-700">危険な操作</h3>
                </div>
                <div class="card-body">
                    <p class="text-gray-600 mb-4">
                        アカウントを削除すると、すべてのデータが永久に失われます。この操作は取り消すことができません。
                    </p>
                    <button type="button"
                        class="btn btn-danger"
                        @click="$dispatch('open-delete-modal')">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        アカウントを削除
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- アカウント削除確認モーダル -->
    <div x-data="{ showDeleteModal: false }"
        @open-delete-modal.window="showDeleteModal = true">
        <div x-show="showDeleteModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto"
            x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showDeleteModal = false"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-red-600">アカウント削除の確認</h3>
                        <button @click="showDeleteModal = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-gray-600">
                            本当にアカウントを削除しますか？この操作は取り消すことができません。
                        </p>

                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('DELETE')

                            <div class="form-group">
                                <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    確認のため、パスワードを入力してください:
                                </label>
                                <input type="password"
                                    id="delete_password"
                                    name="password"
                                    class="form-input"
                                    required>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" @click="showDeleteModal = false" class="btn btn-secondary">
                                    キャンセル
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    削除する
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.avatarPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
    @endpush
</x-app-layout>