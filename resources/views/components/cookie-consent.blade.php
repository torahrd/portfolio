@if(config('analytics.enabled') && config('analytics.cookie_consent.enabled'))
<div 
    x-data="cookieConsent()" 
    x-show="!accepted" 
    x-transition
    class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 z-50 shadow-lg">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex-1">
                <p class="text-sm md:text-base">
                    当サイトでは、サービス向上のためにCookieを使用しています。
                    詳細は<a href="/privacy-policy" class="underline hover:text-gray-300">プライバシーポリシー</a>をご確認ください。
                </p>
            </div>
            <div class="flex gap-3">
                <button 
                    @click="acceptCookies()" 
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-medium transition-colors">
                    同意する
                </button>
                <button 
                    @click="declineCookies()" 
                    class="px-6 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg text-sm font-medium transition-colors">
                    拒否する
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cookieConsent() {
    return {
        accepted: false,
        cookieName: '{{ config('analytics.cookie_consent.cookie_name') }}',
        
        init() {
            // Cookie同意状態を確認
            const consent = this.getCookie(this.cookieName);
            if (consent !== null) {
                this.accepted = true;
                if (consent === 'accepted') {
                    this.enableAnalytics();
                }
            }
        },
        
        acceptCookies() {
            this.setCookie(this.cookieName, 'accepted', {{ config('analytics.cookie_consent.cookie_lifetime') }});
            this.accepted = true;
            this.enableAnalytics();
        },
        
        declineCookies() {
            this.setCookie(this.cookieName, 'declined', {{ config('analytics.cookie_consent.cookie_lifetime') }});
            this.accepted = true;
            this.disableAnalytics();
        },
        
        enableAnalytics() {
            // GA4の同意状態を更新
            if (typeof gtag !== 'undefined') {
                gtag('consent', 'update', {
                    'analytics_storage': 'granted',
                    'ad_storage': 'granted'
                });
                
                // カスタムイベント送信
                gtag('event', 'cookie_consent', {
                    'consent_type': 'accepted'
                });
            }
        },
        
        disableAnalytics() {
            // GA4の同意状態を更新
            if (typeof gtag !== 'undefined') {
                gtag('consent', 'update', {
                    'analytics_storage': 'denied',
                    'ad_storage': 'denied'
                });
                
                // カスタムイベント送信
                gtag('event', 'cookie_consent', {
                    'consent_type': 'declined'
                });
            }
        },
        
        setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            const expires = "expires=" + date.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/;SameSite=Lax";
        },
        
        getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for(let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) {
                    return c.substring(nameEQ.length, c.length);
                }
            }
            return null;
        }
    }
}
</script>
@endif