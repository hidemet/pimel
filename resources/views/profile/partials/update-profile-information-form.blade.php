<section>
    <p class="mb-3 text-muted small">
        {{ __("Aggiorna le informazioni del profilo e l'indirizzo email del tuo account.") }}
    </p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Nome') }}</label>
            <input id="name" name="name" type="text"
                class="form-control @error('name', 'updateProfileInformation') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name', 'updateProfileInformation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email"
                class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror"
                value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email', 'updateProfileInformation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-muted">
                        {{ __('Il tuo indirizzo email non è verificato.') }}
                        <button form="send-verification" class="btn btn-link btn-sm p-0 text-decoration-none">
                            {{ __('Clicca qui per inviare nuovamente l\'email di verifica.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 small text-success">
                            {{ __('Un nuovo link di verifica è stato inviato al tuo indirizzo email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">{{ __('Salva') }}</button>
            {{-- Il messaggio "Saved" automatico di Breeze con Alpine.js è rimosso.
                 Gestiamo i messaggi di successo a livello di pagina in edit.blade.php --}}
        </div>
    </form>
</section>
