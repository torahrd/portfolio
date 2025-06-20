{{-- テキスト入力 --}}
@props([
'type' => 'text',
'name' => '',
'value' => '',
'placeholder' => '',
'required' => false,
'disabled' => false
])

<input type="{{ $type }}"
  name="{{ $name }}"
  value="{{ old($name, $value) }}"
  placeholder="{{ $placeholder }}"
  {{ $required ? 'required' : '' }}
  {{ $disabled ? 'disabled' : '' }}
  {{ $attributes->merge(['class' => 'block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-500']) }}>