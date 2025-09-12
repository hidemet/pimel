@props(['items' => []])

@if (!empty($items))
    <nav aria-label="breadcrumb" {{ $attributes->merge(['class' => 'mb-3']) }}>
        <ol class="breadcrumb">
            @foreach ($items as $item)
                @if ($loop->last && empty($item['url']))
                    <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ $item['url'] ?? '#' }}">{{ $item['label'] }}</a></li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
