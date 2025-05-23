<x-guest-layout>
    @section('title', 'Verifica Email - PIMEL')

    <div class="card shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/pimel_logo_dark_mode.svg') }}"
                        alt="{{ config('app.name', 'PIMEL') }} Logo" height="50">
                </a>
            </div>
            <h1 class="h4 card-title text-center mb-3">{{ __('Verifica il tuo Indirizzo Email') }}</h1>

            <p class="text-muted text-center small mb-4">
                {{ __('Grazie per esserti registrato! Prima di iniziare, potresti verificare il tuo indirizzo email cliccando sul link che ti abbiamo appena inviato? Se non hai ricevuto l\'email, te ne invieremo volentieri un\'altra.') }}
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success mb-3 small py-2" role="alert">
                    {{ __('Un nuovo link di verifica è stato inviato all\'indirizzo email che hai fornito durante la registrazione.') }}
                </div>
            @endif

            <div class="mt-4 d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
                <form method="POST" action="{{ route('verification.send') }}" class="d-grid w-100 w-sm-auto">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        {{ __('Invia nuovamente Email di Verifica') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="d-grid w-100 w-sm-auto mt-2 mt-sm-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        {{ __('Esci') }}
                    </button>
                </form>
            </div>
        </div> {{-- Fine card-body --}}
        <div class="card-footer text-center py-3 bg-light border-top-0">
            <p class="mb-0 small">Hai bisogno di aiuto? <a href="{{ route('contatti.form') }}">Contattaci</a></p>
        </div>
    </div> {{-- Fine card --}}
</x-guest-layout>
