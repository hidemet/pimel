@props(['title', 'subtitle' => null])

<header class="py-4 pt-md-4"> {{-- Classe per eventuale styling specifico --}}
    <div class="container">
        <div class="row justify-content-center"> {{-- Centra la colonna --}}
            <div class="col-lg-10 text-center"> {{-- AGGIUNTO text-center e colonna più larga per contenuto centrato --}}
                <h1 class="display-6 fw-semibold mb-2 mt-2">{{ $title }}</h1>
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
