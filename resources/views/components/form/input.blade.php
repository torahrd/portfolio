@props([
'label' => null,
'name',
'type' => 'text',
'required' => false,
'help' => null,
'placeholder' => null,
'value' => null
])

<div class="form-field">
  @if($label)
  <label for="{{ $name }}" class="form-label {{ $required ? 'required' : '' }}">
    {{ $label }}
  </label>
  @endif

  <input
    type="{{ $type }}"
    id="{{ $name }}"
    name="{{ $name }}"
    class="form-input @error($name) error @enderror"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($value !==null) value="{{ old($name, $value) }}" @else value="{{ old($name) }}" @endif
    @if($required) required @endif
    {{ $attributes }} />

  @error($name)
  <div class="form-error">
    <i class="fas fa-exclamation-circle"></i>
    {{ $message }}
  </div>
  @enderror

  @if($help)
  <div class="form-help">
    {{ $help }}
  </div>
  @endif
</div>