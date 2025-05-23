<x-guest-layout>
    @section('title', 'Conferma Password - PIMEL')

    <div class="card shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/pimel_logo_dark_mode.svg') }}"
                        alt="{{ config('app.name', 'PIMEL') }} Logo" height="50">
                </a>
            </div>
            <h1 class="h4 card-title text-center mb-3">{{ __('Conferma Password') }}</h1>

            <p class="text-muted text-center small mb-4">
                {{ __('Questa è un\'area sicura dell\'applicazione. Per favore, conferma la tua password prima di continuare.') }}
            </p>

            <form method="POST" action="{{ route('password.confirm') }}" novalidate>
                @csrf

                <!-- Password -->
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

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        {{ __('Conferma') }}
                    </button>
                </div>
            </form>
        </div> {{-- Fine card-body --}}
    </div> {{-- Fine card --}}
</x-guest-layout>
