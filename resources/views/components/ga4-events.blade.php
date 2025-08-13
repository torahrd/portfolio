{{-- GA4カスタムイベント用JavaScript --}}
@if(config('analytics.enabled'))
<script>
    // GA4イベント送信ヘルパー関数
    window.sendGA4Event = function(eventName, parameters = {}) {
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, parameters);
            console.log('GA4 Event sent:', eventName, parameters);
        }
    };
    
    // セッションデータからイベントを自動発火
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('ga4_event'))
            @php
                $event = session('ga4_event');
                $params = session('ga4_params', []);
            @endphp
            sendGA4Event('{{ $event }}', {!! json_encode($params) !!});
            {{ session()->forget(['ga4_event', 'ga4_params']) }}
        @endif
    });
</script>
@endif