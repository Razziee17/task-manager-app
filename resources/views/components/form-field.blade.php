@props([
    'label',
    'name',
    'type' => 'text',
    'autocomplete' => null,
    'maxlength' => null,
    'minlength' => null,
    'hint' => null,
    'required' => false,
    'autofocus' => false,
])

<label {{ $attributes->class(['form-field']) }}>
    <span>{{ $label }}</span>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        @if ($type !== 'password') value="{{ old($name) }}" @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if ($maxlength) maxlength="{{ $maxlength }}" @endif
        @if ($minlength) minlength="{{ $minlength }}" @endif
        @required($required)
        @if ($autofocus) autofocus @endif
    >
    @if ($hint)
        <small class="form-hint">{{ $hint }}</small>
    @endif
    @error($name)
        <small class="field-error">{{ $message }}</small>
    @enderror
</label>
