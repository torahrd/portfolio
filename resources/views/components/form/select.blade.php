@props([
'label' => null,
'name',
'required' => false,
'help' => null,
'placeholder' => null,
'value' => null,
'options' => []
])

<div class="form-field">
  @if($label)
  <label for="{{ $name }}" class="form-label {{ $required ? 'required' : '' }}">
    {{ $label }}
  </label>
  @endif

  <select
    id="{{ $name }}"
    name="{{ $name }}"
    class="form-select @error($name) error @enderror"
    @if($required) required @endif
    {{ $attributes }}>
    @if($placeholder)
    <option value="">{{ $placeholder }}</option>
    @endif

    @foreach($options as $optionValue => $optionLabel)
    <option value="{{ $optionValue }}"
      {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
      {{ $optionLabel }}
    </option>
    @endforeach
  </select>

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