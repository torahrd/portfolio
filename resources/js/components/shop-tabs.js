/**
 * 店舗詳細画面のタブコンポーネント（CSP対応）
 * Alpine.js CSPビルドのベストプラクティスに準拠
 */
export function shopTabs() {
    return {
        activeTab: 'info',
        
        /**
         * 情報タブに切り替え
         */
        setInfoTab() {
            this.activeTab = 'info';
        },
        
        /**
         * 投稿タブに切り替え
         */
        setPostsTab() {
            this.activeTab = 'posts';
        },
        
        /**
         * 写真タブに切り替え
         */
        setPhotosTab() {
            this.activeTab = 'photos';
        },
        
        /**
         * 情報タブがアクティブか
         */
        get isInfoActive() {
            return this.activeTab === 'info';
        },
        
        /**
         * 投稿タブがアクティブか
         */
        get isPostsActive() {
            return this.activeTab === 'posts';
        },
        
        /**
         * 写真タブがアクティブか
         */
        get isPhotosActive() {
            return this.activeTab === 'photos';
        },
        
        /**
         * 情報タブのCSSクラス
         */
        get infoTabClass() {
            return this.isInfoActive 
                ? 'border-primary-500 text-primary-600' 
                : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300';
        },
        
        /**
         * 投稿タブのCSSクラス
         */
        get postsTabClass() {
            return this.isPostsActive 
                ? 'border-primary-500 text-primary-600' 
                : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300';
        },
        
        /**
         * 写真タブのCSSクラス
         */
        get photosTabClass() {
            return this.isPhotosActive 
                ? 'border-primary-500 text-primary-600' 
                : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300';
        }
    };
}