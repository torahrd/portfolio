@props([
'business_hours', // ★修正：businessHours → business_hours
'compact' => false
])

@php
$dayNames = ['日', '月', '火', '水', '木', '金', '土'];
$today = now()->dayOfWeek;
@endphp

<div class="bg-white rounded-xl shadow-card p-6">
    <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        営業時間
    </h3>

    {{-- ★修正：$businessHours → $business_hours --}}
    @if($business_hours && $business_hours->count() > 0)
    <div class="space-y-2">
        @foreach($dayNames as $dayIndex => $dayName)
        @php
        $hours = $business_hours->where('day', $dayIndex)->first();
        $isToday = $today === $dayIndex;
        @endphp
        <div class="flex items-center justify-between py-2 px-3 rounded-lg {{ $isToday ? 'bg-primary-50 border border-primary-200' : 'hover:bg-neutral-50' }} transition-colors duration-200">
            <span class="font-medium {{ $isToday ? 'text-primary-700' : 'text-neutral-700' }}">
                {{ $dayName }}曜日
                @if($isToday)
                <span class="text-xs bg-primary-500 text-white px-2 py-0.5 rounded-full ml-2">今日</span>
                @endif
            </span>
            <span class="{{ $isToday ? 'text-primary-600 font-semibold' : 'text-neutral-600' }}">
                @if($hours)
                {{ substr($hours->open_time, 0, 5) }} - {{ substr($hours->close_time, 0, 5) }}
                @else
                <span class="text-neutral-400">定休日</span>
                @endif
            </span>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-6 text-neutral-500">
        <svg class="mx-auto h-12 w-12 text-neutral-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-sm">営業時間の情報がありません</p>
    </div>
    @endif
</div>