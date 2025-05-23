<x-guest-layout>
    @section('title', 'Registrati - PIMEL')

    <div class="card shadow-sm">
        {{-- RIMOSSO IL card-header con i nav-pills --}}
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4"> {{-- Spostato il logo qui --}}
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/pimel_logo_dark_mode.svg') }}"
                        alt="{{ config('app.name', 'PIMEL') }} Logo" height="50">
                </a>
            </div>
            <h1 class="h4 card-title text-center mb-4">Crea il Tuo Account PIMEL</h1>

            @if ($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('password'))
                <div class="alert alert-danger mb-3 small py-2">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Nome Completo') }}</label>
                    <input id="name" class="form-control @error('name') is-invalid @enderror" type="text"
                        name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Indirizzo Email') }}</label>
                    <input id="email" class="form-control @error('email') is-invalid @enderror" type="email"
                        name="email" value="{{ old('email') }}" required autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" class="form-control @error('password') is-invalid @enderror" type="password"
                        name="password" required autocomplete="new-password"
                        aria-describedby="passwordHelpBlockRegister">
                    <div id="passwordHelpBlockRegister" class="form-text small">
                        La password deve contenere almeno 8 caratteri.
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Conferma Password') }}</label>
                    <input id="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror" type="password"
                        name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        {{ __('Registrati') }}
                    </button>
                </div>
            </form>
            {{-- Spostato il link "Hai già un account?" qui --}}
            <div class="text-center mt-4">
                <p class="mb-0 small">Hai già un account? <a href="{{ route('login') }}">Accedi qui</a></p>
            </div>
        </div> {{-- Fine card-body --}}
        {{-- RIMOSSO il card-footer che conteneva il link a Login --}}
    </div> {{-- Fine card --}}
</x-guest-layout>
