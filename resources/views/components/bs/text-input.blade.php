{{-- resources/views/components/bs/text-input.blade.php --}}
@props(['disabled' => false, 'id', 'name', 'type' => 'text', 'value' => null, 'isInvalid' => false])
<input @if ($disabled) disabled @endif id="{{ $id }}" name="{{ $name }}"
    type="{{ $type }}" value="{{ $value }}"
    {{ $attributes->class(['form-control', 'is-invalid' => $isInvalid]) }}>
