<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-2xl text-neutral-800 leading-tight flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white">
          <i class="fas fa-plus text-lg"></i>
        </div>
        新しい投稿を作成
      </h2>

      <div class="flex items-center gap-2 text-sm text-neutral-600">
        <i class="fas fa-info-circle"></i>
        <span>お気に入りの店舗を共有しましょう</span>
      </div>
    </div>
  </x-slot>

  <div class="max-w-4xl mx-auto">
    <!-- プログレスインジケーター -->
    <div class="form-progress mb-8 animate-slide-down">
      <div class="form-progress-fill" style="width: 0%" id="progress-fill"></div>
    </div>

    <!-- フォームステップ -->
    <div class="form-steps mb-8 animate-slide-down">
      <div class="form-step active" data-step="1">
        <div class="form-step-number">1</div>
        <span class="hidden sm:inline">店舗情報</span>
      </div>
      <div class="form-step pending" data-step="2">
        <div class="form-step-number">2</div>
        <span class="hidden sm:inline">体験詳細</span>
      </div>
      <div class="form-step pending" data-step="3">
        <div class="form-step-number">3</div>
        <span class="hidden sm:inline">確認・投稿</span>
      </div>
    </div>

    <!-- メインフォーム -->
    <form method="POST" action="{{ route('posts.store') }}"
      class="glass-card p-8 animate-fade-in"
      x-data="postForm()"
      @submit.prevent="submitForm"
      enctype="multipart/form-data">
      @csrf

      <!-- ステップ1: 店舗情報 -->
      <div class="form-step-content" id="step-1">
        <div class="mb-8">
          <h3 class="text-2xl font-bold text-gradient-mocha mb-2">
            <i class="fas fa-store mr-3"></i>
            店舗情報を入力
          </h3>
          <p class="text-neutral-600 leading-relaxed">
            共有したい店舗の基本情報を入力してください。店舗名を入力すると、候補が表示されます。
          </p>
        </div>

        <!-- 店舗検索 -->
        <div class="form-group">
          <label for="shop_search" class="form-label required">
            <i class="fas fa-search mr-2"></i>
            店舗を検索
          </label>
          <div class="shop-search-container">
            <input type="text"
              id="shop_search"
              name="shop_search"
              class="shop-search-input form-input-base"
              placeholder="例: スターバックス 渋谷店"
              x-model="searchQuery"
              @input.debounce.300ms="searchShops"
              @focus="showResults = true"
              autocomplete="off">
            <i class="shop-search-icon fas fa-search"></i>

            <!-- 検索結果 -->
            <div class="search-results"
              x-show="showResults && searchResults.length > 0"
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 scale-95"
              x-transition:enter-end="opacity-1 scale-100">
              <template x-for="shop in searchResults" :key="shop.id">
                <div class="search-result-item"
                  @click="selectShop(shop)">
                  <i class="shop-icon fas fa-store"></i>
                  <div class="shop-info">
                    <div class="shop-name" x-text="shop.name"></div>
                    <div class="shop-address" x-text="shop.address"></div>
                  </div>
                </div>
              </template>
            </div>
          </div>
          <div class="form-help-text">
            <i class="fas fa-lightbulb mr-1"></i>
            店舗が見つからない場合は、新しく登録することができます
          </div>
        </div>

        <!-- 選択された店舗情報 -->
        <div x-show="selectedShop"
          class="form-group">
          <div class="glass-subtle rounded-xl p-4 border border-mocha-200">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white flex-shrink-0">
                <i class="fas fa-store"></i>
              </div>
              <div class="flex-1">
                <h4 class="font-semibold text-neutral-900 mb-1" x-text="selectedShop?.name"></h4>
                <p class="text-sm text-neutral-600" x-text="selectedShop?.address"></p>
                <button type="button"
                  @click="clearSelection"
                  class="text-xs text-coral-600 hover:text-coral-700 mt-2">
                  <i class="fas fa-times mr-1"></i>
                  選択を解除
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- 新規店舗登録 -->
        <div x-show="!selectedShop || showNewShopForm"
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 transform scale-95"
          x-transition:enter-end="opacity-1 transform scale-100">

          <div class="form-group-split">
            <div class="form-group">
              <label for="shop_name" class="form-label required">店舗名</label>
              <input type="text"
                id="shop_name"
                name="shop_name"
                class="form-input-base @error('shop_name') form-error-state @enderror"
                placeholder="例: スターバックス コーヒー 渋谷店"
                x-model="newShop.name"
                :value="selectedShop?.name || ''"
                required>
              @error('shop_name')
              <div class="form-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
              </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="shop_address" class="form-label">住所</label>
              <input type="text"
                id="shop_address"
                name="shop_address"
                class="form-input-base @error('shop_address') form-error-state @enderror"
                placeholder="例: 東京都渋谷区道玄坂1-1-1"
                x-model="newShop.address"
                :value="selectedShop?.address || ''">
              @error('shop_address')
              <div class="form-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
              </div>
              @enderror
            </div>
          </div>
        </div>

        <!-- 隠しフィールド -->
        <input type="hidden" name="shop_id" x-model="selectedShop?.id">
      </div>

      <!-- ステップ2: 体験詳細 -->
      <div class="form-step-content hidden" id="step-2">
        <div class="mb-8">
          <h3 class="text-2xl font-bold text-gradient-mocha mb-2">
            <i class="fas fa-heart mr-3"></i>
            体験を共有
          </h3>
          <p class="text-neutral-600 leading-relaxed">
            この店舗での体験や感想を詳しく教えてください。他のユーザーにとって参考になる情報をお願いします。
          </p>
        </div>

        <!-- 訪問ステータス -->
        <div class="form-group">
          <label class="form-label required">
            <i class="fas fa-flag mr-2"></i>
            訪問ステータス
          </label>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-radio-item" :class="{ 'checked': visitStatus === true }">
              <input type="radio"
                id="visited"
                name="visit_status"
                value="1"
                class="form-radio"
                x-model="visitStatus"
                :value="true">
              <label for="visited" class="flex items-center gap-3 cursor-pointer">
                <div class="flex-1">
                  <div class="font-semibold text-success-700">
                    <i class="fas fa-check-circle mr-2"></i>
                    訪問済み
                  </div>
                  <div class="text-sm text-neutral-600">
                    実際に行ったことがある店舗
                  </div>
                </div>
              </label>
            </div>

            <div class="form-radio-item" :class="{ 'checked': visitStatus === false }">
              <input type="radio"
                id="want_to_visit"
                name="visit_status"
                value="0"
                class="form-radio"
                x-model="visitStatus"
                :value="false">
              <label for="want_to_visit" class="flex items-center gap-3 cursor-pointer">
                <div class="flex-1">
                  <div class="font-semibold text-warning-700">
                    <i class="fas fa-heart mr-2"></i>
                    行きたい
                  </div>
                  <div class="text-sm text-neutral-600">
                    これから行ってみたい店舗
                  </div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- 訪問日時（訪問済みの場合のみ） -->
        <div x-show="visitStatus === true"
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 transform -translate-y-4"
          x-transition:enter-end="opacity-1 transform translate-y-0"
          class="form-group">
          <label for="visit_time" class="form-label">
            <i class="fas fa-calendar-alt mr-2"></i>
            訪問日時
          </label>
          <input type="datetime-local"
            id="visit_time"
            name="visit_time"
            class="form-input-base @error('visit_time') form-error-state @enderror"
            x-model="visitTime">
          @error('visit_time')
          <div class="form-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $message }}
          </div>
          @enderror
        </div>

        <!-- 投稿内容 -->
        <div class="form-group">
          <label for="body" class="form-label required">
            <i class="fas fa-pen mr-2"></i>
            体験・感想
          </label>
          <textarea id="body"
            name="body"
            rows="6"
            class="form-textarea-base @error('body') form-error-state @enderror"
            placeholder="この店舗での体験や感想を詳しく教えてください...&#10;&#10;例:&#10;- 料理の味や雰囲気について&#10;- おすすめのメニュー&#10;- 特別なサービスや体験&#10;- その他の感想"
            x-model="postBody"
            @input="updateCharacterCount"
            required></textarea>

          <!-- 文字数カウンター -->
          <div class="form-counter" :class="{ 'warning': characterCount > 450, 'error': characterCount > 500 }">
            <span>
              <span x-text="characterCount"></span> / 500文字
            </span>
            <div class="form-counter-progress">
              <div class="form-counter-fill" :style="`width: ${Math.min(characterCount / 500 * 100, 100)}%`"></div>
            </div>
          </div>

          @error('body')
          <div class="form-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $message }}
          </div>
          @enderror
        </div>

        <!-- 予算 -->
        <div class="form-group">
          <label for="budget" class="form-label">
            <i class="fas fa-yen-sign mr-2"></i>
            予算（一人当たり）
          </label>
          <select id="budget"
            name="budget"
            class="form-select-base @error('budget') form-error-state @enderror"
            x-model="budget">
            <option value="">予算を選択してください</option>
            <option value="1000">〜1,000円</option>
            <option value="2000">1,000円〜2,000円</option>
            <option value="3000">2,000円〜3,000円</option>
            <option value="5000">3,000円〜5,000円</option>
            <option value="10000">5,000円〜10,000円</option>
            <option value="20000">10,000円〜20,000円</option>
            <option value="30000">20,000円以上</option>
          </select>
          @error('budget')
          <div class="form-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $message }}
          </div>
          @enderror
        </div>

        <!-- おすすめメニュー -->
        <div class="form-group">
          <label for="menus" class="form-label">
            <i class="fas fa-utensils mr-2"></i>
            おすすめメニュー
          </label>
          <textarea id="menus"
            name="menus"
            rows="3"
            class="form-textarea-base @error('menus') form-error-state @enderror"
            placeholder="おすすめのメニューがあれば教えてください...&#10;&#10;例: ハンバーガーセット、パスタ、デザートなど"
            x-model="menus"></textarea>
          @error('menus')
          <div class="form-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $message }}
          </div>
          @enderror
        </div>

        <!-- 参考URL -->
        <div class="form-group">
          <label for="reference_url" class="form-label">
            <i class="fas fa-link mr-2"></i>
            参考URL
          </label>
          <input type="url"
            id="reference_url"
            name="reference_url"
            class="form-input-base @error('reference_url') form-error-state @enderror"
            placeholder="https://example.com"
            x-model="referenceUrl">
          <div class="form-help-text">
            <i class="fas fa-info-circle mr-1"></i>
            店舗の公式サイトやグルメサイトのURLなど
          </div>
          @error('reference_url')
          <div class="form-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ $message }}
          </div>
          @enderror
        </div>
      </div>

      <!-- ステップ3: 確認・投稿 -->
      <div class="form-step-content hidden" id="step-3">
        <div class="mb-8">
          <h3 class="text-2xl font-bold text-gradient-mocha mb-2">
            <i class="fas fa-check mr-3"></i>
            内容を確認
          </h3>
          <p class="text-neutral-600 leading-relaxed">
            入力した内容を確認して、問題なければ投稿してください。投稿後も編集可能です。
          </p>
        </div>

        <!-- プレビュー -->
        <div class="glass-subtle rounded-2xl p-6 mb-6">
          <h4 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center gap-2">
            <i class="fas fa-eye text-mocha-500"></i>
            投稿プレビュー
          </h4>

          <div class="post-item border-0 shadow-none p-0 mb-0">
            <!-- プレビューヘッダー -->
            <div class="post-header">
              <img src="{{ auth()->user()->avatar_url }}"
                alt="{{ auth()->user()->name }}"
                class="post-avatar">
              <div class="post-author-info">
                <div class="post-author">{{ auth()->user()->name }}</div>
                <div class="post-meta">
                  <span><i class="fas fa-clock"></i> たった今</span>
                  <span x-show="visitStatus === true && visitTime" class="visit-status-badge visit-status-visited">
                    <i class="fas fa-check-circle"></i>
                    訪問済み
                  </span>
                  <span x-show="visitStatus === false" class="visit-status-badge visit-status-planned">
                    <i class="fas fa-heart"></i>
                    行きたい
                  </span>
                </div>
              </div>
            </div>

            <!-- プレビュー内容 -->
            <div class="post-content">
              <div class="post-shop-info">
                <h3 class="text-xl font-semibold text-neutral-900 mb-2">
                  <i class="fas fa-store mr-2"></i>
                  <span x-text="selectedShop?.name || newShop.name || '店舗名'"></span>
                </h3>
                <p x-show="selectedShop?.address || newShop.address"
                  class="text-sm text-neutral-600 mb-3 flex items-center gap-2">
                  <i class="fas fa-map-marker-alt text-neutral-400"></i>
                  <span x-text="selectedShop?.address || newShop.address"></span>
                </p>
              </div>

              <div x-show="postBody" class="mb-4">
                <p class="text-neutral-700 leading-relaxed whitespace-pre-line" x-text="postBody"></p>
              </div>

              <div x-show="budget" class="mb-4">
                <span class="post-budget">
                  <i class="fas fa-yen-sign"></i>
                  予算: <span x-text="getBudgetText(budget)"></span>
                </span>
              </div>

              <div x-show="menus" class="mb-4 p-4 bg-gradient-to-r from-sage-50 to-sage-100 rounded-xl border border-sage-200">
                <h4 class="text-sm font-semibold text-sage-800 mb-2 flex items-center gap-2">
                  <i class="fas fa-utensils text-sage-600"></i>
                  おすすめメニュー
                </h4>
                <p class="text-sage-700 text-sm whitespace-pre-line" x-text="menus"></p>
              </div>

              <div x-show="referenceUrl" class="mb-4">
                <a :href="referenceUrl"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="inline-flex items-center gap-2 text-electric-600 hover:text-electric-700 text-sm font-medium">
                  <i class="fas fa-external-link-alt"></i>
                  詳細情報を見る
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ナビゲーションボタン -->
      <div class="form-actions">
        <button type="button"
          id="prev-btn"
          class="btn btn-outline-secondary hidden"
          @click="previousStep">
          <i class="fas fa-arrow-left mr-2"></i>
          前へ
        </button>

        <div class="flex-1"></div>

        <button type="button"
          id="next-btn"
          class="btn btn-primary"
          @click="nextStep">
          次へ
          <i class="fas fa-arrow-right ml-2"></i>
        </button>

        <button type="submit"
          id="submit-btn"
          class="btn btn-primary hidden"
          :disabled="isSubmitting">
          <template x-if="isSubmitting">
            <i class="fas fa-spinner animate-spin mr-2"></i>
          </template>
          <template x-if="!isSubmitting">
            <i class="fas fa-paper-plane mr-2"></i>
          </template>
          投稿する
        </button>
      </div>
    </form>
  </div>

  <!-- Alpine.js Script -->
  <script>
    function postForm() {
      return {
        currentStep: 1,
        totalSteps: 3,
        isSubmitting: false,

        // フォームデータ
        searchQuery: '',
        showResults: false,
        searchResults: [],
        selectedShop: null,
        showNewShopForm: false,
        newShop: {
          name: '',
          address: ''
        },

        visitStatus: null,
        visitTime: '',
        postBody: '',
        characterCount: 0,
        budget: '',
        menus: '',
        referenceUrl: '',

        init() {
          this.updateProgress();
          this.updateCharacterCount();
        },

        // 店舗検索
        async searchShops() {
          if (this.searchQuery.length < 2) {
            this.searchResults = [];
            return;
          }

          try {
            // 実際の実装では、APIエンドポイントを呼び出す
            // const response = await fetch(`/api/shops/search?q=${encodeURIComponent(this.searchQuery)}`);
            // const data = await response.json();

            // デモ用のダミーデータ
            this.searchResults = [{
                id: 1,
                name: 'スターバックス コーヒー 渋谷店',
                address: '東京都渋谷区道玄坂1-1-1'
              },
              {
                id: 2,
                name: 'タリーズコーヒー 新宿店',
                address: '東京都新宿区新宿3-1-1'
              }
            ].filter(shop =>
              shop.name.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
          } catch (error) {
            console.error('Shop search error:', error);
            this.searchResults = [];
          }
        },

        selectShop(shop) {
          this.selectedShop = shop;
          this.searchQuery = shop.name;
          this.showResults = false;
          this.newShop.name = shop.name;
          this.newShop.address = shop.address;
        },

        clearSelection() {
          this.selectedShop = null;
          this.searchQuery = '';
          this.newShop = {
            name: '',
            address: ''
          };
        },

        // ステップ管理
        nextStep() {
          if (this.validateCurrentStep()) {
            if (this.currentStep < this.totalSteps) {
              this.currentStep++;
              this.updateStepDisplay();
              this.updateProgress();
            }
          }
        },

        previousStep() {
          if (this.currentStep > 1) {
            this.currentStep--;
            this.updateStepDisplay();
            this.updateProgress();
          }
        },

        updateStepDisplay() {
          // ステップコンテンツの表示/非表示
          for (let i = 1; i <= this.totalSteps; i++) {
            const stepContent = document.getElementById(`step-${i}`);
            if (i === this.currentStep) {
              stepContent.classList.remove('hidden');
              stepContent.classList.add('animate-slide-up');
            } else {
              stepContent.classList.add('hidden');
              stepContent.classList.remove('animate-slide-up');
            }
          }

          // ステップインジケーターの更新
          document.querySelectorAll('.form-step').forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('active', 'completed', 'pending');

            if (stepNumber < this.currentStep) {
              step.classList.add('completed');
            } else if (stepNumber === this.currentStep) {
              step.classList.add('active');
            } else {
              step.classList.add('pending');
            }
          });

          // ボタンの表示制御
          const prevBtn = document.getElementById('prev-btn');
          const nextBtn = document.getElementById('next-btn');
          const submitBtn = document.getElementById('submit-btn');

          if (this.currentStep === 1) {
            prevBtn.classList.add('hidden');
          } else {
            prevBtn.classList.remove('hidden');
          }

          if (this.currentStep === this.totalSteps) {
            nextBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
          } else {
            nextBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
          }
        },

        updateProgress() {
          const progress = (this.currentStep / this.totalSteps) * 100;
          document.getElementById('progress-fill').style.width = `${progress}%`;
        },

        validateCurrentStep() {
          switch (this.currentStep) {
            case 1:
              if (!this.selectedShop && !this.newShop.name.trim()) {
                this.showError('店舗名を入力してください');
                return false;
              }
              return true;
            case 2:
              if (this.visitStatus === null) {
                this.showError('訪問ステータスを選択してください');
                return false;
              }
              if (!this.postBody.trim()) {
                this.showError('体験・感想を入力してください');
                return false;
              }
              if (this.postBody.length > 500) {
                this.showError('体験・感想は500文字以内で入力してください');
                return false;
              }
              return true;
            case 3:
              return true;
            default:
              return true;
          }
        },

        updateCharacterCount() {
          this.characterCount = this.postBody.length;
        },

        getBudgetText(budget) {
          const budgetMap = {
            '1000': '〜1,000円',
            '2000': '1,000円〜2,000円',
            '3000': '2,000円〜3,000円',
            '5000': '3,000円〜5,000円',
            '10000': '5,000円〜10,000円',
            '20000': '10,000円〜20,000円',
            '30000': '20,000円以上'
          };
          return budgetMap[budget] || '';
        },

        async submitForm() {
          if (!this.validateCurrentStep()) return;

          this.isSubmitting = true;

          try {
            // 実際のフォーム送信
            const form = this.$el;
            const formData = new FormData(form);

            const response = await fetch(form.action, {
              method: 'POST',
              body: formData,
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              }
            });

            if (response.ok) {
              // 成功時の処理
              window.location.href = '/posts';
            } else {
              throw new Error('投稿に失敗しました');
            }
          } catch (error) {
            this.showError(error.message);
            this.isSubmitting = false;
          }
        },

        showError(message) {
          // エラーメッセージを表示
          const toast = document.createElement('div');
          toast.className = 'toast error';
          toast.innerHTML = `
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        ${message}
                    `;
          document.body.appendChild(toast);

          setTimeout(() => {
            document.body.removeChild(toast);
          }, 5000);
        }
      }
    }

    // 外部クリックで検索結果を閉じる
    document.addEventListener('click', function(event) {
      const searchContainer = document.querySelector('.shop-search-container');
      if (searchContainer && !searchContainer.contains(event.target)) {
        // Alpine.jsのデータにアクセス
        const form = document.querySelector('[x-data]');
        if (form && form._x_dataStack && form._x_dataStack[0]) {
          form._x_dataStack[0].showResults = false;
        }
      }
    });
  </script>
</x-app-layout>