@props([
'label' => null,
'name',
'value' => '1',
'checked' => false,
'help' => null
])

<div class="form-field">
  <div class="form-group-horizontal">
    <input
      type="checkbox"
      id="{{ $name }}"
      name="{{ $name }}"
      value="{{ $value }}"
      class="form-checkbox @error($name) error @enderror"
      {{ old($name, $checked) ? 'checked' : '' }}
      {{ $attributes }} />

    @if($label)
    <label for="{{ $name }}" class="form-checkbox-label">
      {{ $label }}
    </label>
    @endif
  </div>

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