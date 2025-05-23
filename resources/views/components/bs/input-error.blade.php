{{-- resources/views/components/bs/input-error.blade.php --}}
@props(['messages'])
@if ($messages)
    @foreach ((array) $messages as $message)
        <div {{ $attributes->merge(['class' => 'invalid-feedback d-block']) }}>{{ $message }}</div>
        {{-- d-block è necessario se l'input non ha is-invalid, o per forzarne la visualizzazione --}}
    @endforeach
@endif
