{{-- resources/views/components/bs/primary-button.blade.php --}}
@props(['type' => 'submit'])
<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn btn-primary']) }}>
    {{ $slot }}
</button>
