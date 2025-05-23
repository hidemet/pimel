<x-app-layout>
    @section('title', 'Mio Profilo - PIMEL')

    {{-- Potresti volere un page-header standard qui se lo usi altrove --}}
    <x-layout.page-header title="Mio Profilo" subtitle="Gestisci le informazioni del tuo account." />

    <div class="container py-4 py-md-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7"> {{-- Puoi aggiustare la larghezza se necessario --}}

                {{-- Messaggio di successo generico per il profilo --}}
                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ __('Profilo aggiornato con successo.') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ __('Password aggiornata con successo.') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                {{-- Altri messaggi di sessione se necessario --}}


                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h2 class="h5 mb-0">{{ __('Informazioni Profilo') }}</h2>
                    </div>
                    <div class="card-body p-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h2 class="h5 mb-0">{{ __('Aggiorna Password') }}</h2>
                    </div>
                    <div class="card-body p-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h2 class="h5 mb-0">{{ __('Elimina Account') }}</h2>
                    </div>
                    <div class="card-body p-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
