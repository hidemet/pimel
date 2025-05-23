<x-guest-layout>
    @section('title', 'Recupera Password - PIMEL')

    <div class="card shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/pimel_logo_dark_mode.svg') }}"
                        alt="{{ config('app.name', 'PIMEL') }} Logo" height="50">
                </a>
            </div>
            <h1 class="h4 card-title text-center mb-3">Password Dimenticata?</h1>

            <p class="text-muted text-center small mb-4">
                Nessun problema. Inserisci il tuo indirizzo email e ti invieremo un link per resettare la password e
                sceglierne una nuova.
            </p>

            <!-- Session Status (es. "Ti abbiamo inviato il link per il reset della password!") -->
            @if (session('status'))
                <div class="alert alert-success mb-3 small py-2" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Eventuali errori generali -->
            @if ($errors->any() && !$errors->has('email'))
                <div class="alert alert-danger mb-3 small py-2">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Indirizzo Email') }}</label>
                    <input id="email" class="form-control @error('email') is-invalid @enderror" type="email"
                        name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Invia Link per Reset Password') }}
                    </button>
                </div>
            </form>
        </div> {{-- Fine card-body --}}
        <div class="card-footer text-center py-3 bg-light border-top-0">
            <p class="mb-0 small">Ricordi la password? <a href="{{ route('login') }}">Torna al Login</a></p>
        </div>
    </div> {{-- Fine card --}}
</x-guest-layout>
