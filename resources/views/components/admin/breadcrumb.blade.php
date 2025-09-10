{{-- resources/views/components/admin/breadcrumb.blade.php --}}
@props(['items' => []])

@if (!empty($items))
    <nav aria-label="breadcrumb" {{ $attributes->merge(['class' => 'mb-3']) }}> {{-- mb-3 per un po' di spazio sotto, puoi aggiustarlo --}}
        <ol class="breadcrumb">
            @foreach ($items as $item)
                @if ($loop->last && empty($item['url']))
                    {{-- L'ultimo item è attivo e non ha URL --}}
                    <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
