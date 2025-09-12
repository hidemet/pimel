<x-app-layout>
    <x-slot name="pageHeader">
        <x-layout.page-header title="La Mia Dashboard" />
    </x-slot>

    <div class="container py-4 py-md-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="h4 card-title mb-3">Bentornato, {{ Auth::user()->name }}!</h2>
                        <p class="text-muted">
                            Questa è la tua area personale. Da qui puoi gestire il tuo profilo e le tue preferenze.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary me-2">
                                <span class="material-symbols-outlined fs-6 align-middle me-1">person</span>
                                Modifica Profilo
                            </a>
                            @if (Route::has('profile.newsletter.edit'))
                                <a href="{{ route('profile.newsletter.edit') }}" class="btn btn-outline-secondary">
                                    <span class="material-symbols-outlined fs-6 align-middle me-1">mail</span>
                                    Preferenze Newsletter
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
