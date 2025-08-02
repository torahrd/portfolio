// テスト用カウンターコンポーネント（CSPビルド対応版）
export function testCounter() {
    return {
        count: 0,
        
        increment() {
            this.count++;
            console.log('Increment called, count:', this.count);
            // CSPビルドでのリアクティブ更新を確実にする
            this.$nextTick(() => {
                console.log('Count updated in DOM:', this.count);
            });
        },
        
        decrement() {
            this.count--;
            console.log('Decrement called, count:', this.count);
            // CSPビルドでのリアクティブ更新を確実にする
            this.$nextTick(() => {
                console.log('Count updated in DOM:', this.count);
            });
        }
    };
} 