@props(['type' => 'default'])

@if($type === 'simple')
    <!-- シンプルフッター（現在のapp.blade.php用） -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-sm text-gray-500">
                    © 2025 TasteRetreat. All rights reserved.
                </div>
                <div class="flex space-x-6">
                    <a href="{{ route('privacy-policy') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        プライバシーポリシー
                    </a>
                </div>
            </div>
        </div>
    </footer>
@else
    <!-- 詳細フッター（guest.blade.php用、およびapp.blade.php用） -->
    <footer class="bg-white border-t border-neutral-200 mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-primary-500 rounded flex items-center justify-center">
                            <span class="text-white font-bold text-sm">T+</span>
                        </div>
                        <span class="text-xl font-bold text-neutral-900">TasteRetreat</span>
                    </div>
                    <p class="text-sm text-neutral-600 mb-4">
                        美味しいお店を発見し、グルメ体験を共有する新しいプラットフォーム
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-neutral-500 hover:text-neutral-700 transition-colors">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="text-neutral-500 hover:text-neutral-700 transition-colors">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="text-neutral-500 hover:text-neutral-700 transition-colors">
                            <i class="fab fa-facebook text-lg"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-neutral-800 font-semibold mb-4 text-lg">サポート</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <ul class="space-y-3">
                            <li><a href="{{ route('contact') }}" class="text-sm text-neutral-600 hover:text-neutral-800 transition-colors">よくある質問</a></li>
                            <li><a href="{{ route('contact') }}" class="text-sm text-neutral-600 hover:text-neutral-800 transition-colors">お問い合わせ</a></li>
                        </ul>
                        <ul class="space-y-3">
                            <li><a href="{{ route('privacy-policy') }}" class="text-sm text-neutral-600 hover:text-neutral-800 transition-colors">プライバシーポリシー</a></li>
                            <li><a href="{{ route('terms') }}" class="text-sm text-neutral-600 hover:text-neutral-800 transition-colors">利用規約</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-neutral-200 mt-8 pt-6 text-center text-sm text-neutral-500">
                © 2025 TasteRetreat. All rights reserved.
            </div>
        </div>
    </footer>
@endif 