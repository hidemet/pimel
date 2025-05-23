<x-guest-layout>
    @section('title', 'Reimposta Password - PIMEL')

    <div class="card shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/pimel_logo_dark_mode.svg') }}"
                        alt="{{ config('app.name', 'PIMEL') }} Logo" height="50">
                </a>
            </div>
            <h1 class="h4 card-title text-center mb-3">{{ __('Reimposta Password') }}</h1>

            <!-- Eventuali errori generali -->
            @if (
                $errors->any() &&
                    !$errors->has('email') &&
                    !$errors->has('password') &&
                    !$errors->has('password_confirmation') &&
                    !$errors->has('token'))
                <div class="alert alert-danger mb-3 small py-2">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Indirizzo Email') }}</label>
                    <input id="email" class="form-control @error('email') is-invalid @enderror" type="email"
                        name="email" value="{{ old('email', $request->email) }}" required autofocus
                        autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Nuova Password') }}</label>
                    <input id="password" class="form-control @error('password') is-invalid @enderror" type="password"
                        name="password" required autocomplete="new-password" aria-describedby="passwordHelpBlockReset">
                    <div id="passwordHelpBlockReset" class="form-text small">
                        La password deve contenere almeno 8 caratteri.
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Conferma Nuova Password') }}</label>
                    <input id="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror" type="password"
                        name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        {{ __('Reimposta Password') }}
                    </button>
                </div>
            </form>
        </div> {{-- Fine card-body --}}
    </div> {{-- Fine card --}}
</x-guest-layout>
