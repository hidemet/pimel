@props(['title', 'subtitle' => null, 'bgClass' => 'bg-default']) {{-- Aggiunto bgClass per la classe di sfondo --}}
{{-- Aggiunta la classe "bg-surface-blog" per lo sfondo --}}

<header class="py-5 {{$bgClass}} "> {{-- Classe per eventuale styling specifico --}}
    <div class="container">
        <div class="row justify-content-center"> {{-- Centra la colonna --}}
            <div class="col-lg-9 col-xl-7 text-center"> {{-- AGGIUNTO text-center e colonna più larga per contenuto centrato --}}
                <h1 class="display-4 fw-semibold mb-2 mt-2">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="lead text-muted mb-3">
                        {!! $subtitle !!}
                    </p>
                @endif
                {{ $slot }} {{-- Anche lo slot sarà centrato --}}
            </div>
        </div>
    </div>
</header>
