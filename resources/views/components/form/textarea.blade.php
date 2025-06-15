@props([
'label' => null,
'name',
'required' => false,
'help' => null,
'placeholder' => null,
'value' => null,
'rows' => 4
])

<div class="form-field">
  @if($label)
  <label for="{{ $name }}" class="form-label {{ $required ? 'required' : '' }}">
    {{ $label }}
  </label>
  @endif

  <textarea
    id="{{ $name }}"
    name="{{ $name }}"
    class="form-textarea @error($name) error @enderror"
    rows="{{ $rows }}"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($required) required @endif
    {{ $attributes }}>{{ old($name, $value) }}</textarea>

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