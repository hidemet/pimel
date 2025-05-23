<x-guest-layout>
    @section('title', 'Accedi - PIMEL')

    <div class="card shadow-sm">
        {{-- RIMOSSO IL card-header con i nav-pills --}}
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4"> {{-- Spostato il logo qui per coerenza se si rimuove l'header --}}
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/pimel_logo_dark_mode.svg') }}"
                        alt="{{ config('app.name', 'PIMEL') }} Logo" height="50">
                </a>
            </div>
            <h1 class="h4 card-title text-center mb-4">Accedi al tuo Account</h1>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-3 small py-2" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Errori di Login Generali -->
            @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
                <div class="alert alert-danger mb-3 small py-2">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Indirizzo Email') }}</label>
                    <input id="email" class="form-control @error('email') is-invalid @enderror" type="email"
                        name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" class="form-control @error('password') is-invalid @enderror" type="password"
                        name="password" required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label class="form-check-label small" for="remember_me">{{ __('Ricordami') }}</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="text-decoration-none small" href="{{ route('password.request') }}">
                            {{ __('Password dimenticata?') }}
                        </a>
                    @endif
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        {{ __('Accedi') }}
                    </button>
                </div>
            </form>
            {{-- Spostato il link "Non hai un account?" qui, fuori dal footer della card --}}
            <div class="text-center mt-4">
                <p class="mb-0 small">Non hai un account? <a href="{{ route('register') }}">Registrati ora</a></p>
            </div>
        </div> {{-- Fine card-body --}}
        {{-- RIMOSSO il card-footer che conteneva il link a Registrati --}}
    </div> {{-- Fine card --}}
</x-guest-layout>
