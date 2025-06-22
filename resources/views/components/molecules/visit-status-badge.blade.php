@props([
'status' => null, // true: 訪問済み, false: 訪問予定
])

@if(!is_null($status))
<div {{ $attributes }}>
  <span @class([ 'px-2 py-1 text-xs font-medium rounded-full' , 'bg-success-100 text-success-800'=> $status,
    'bg-warning-100 text-warning-800' => !$status,
    ])>
    {{ $status ? '訪問済み' : '訪問予定' }}
  </span>
</div>
@endif