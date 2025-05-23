<x-app-layout>
    @section('title', 'Dashboard - PIMEL')

    <x-layout.page-header title="Dashboard" />

    <div class="container py-4 py-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                {{ __('Accesso effettuato!') }}
            </div>
        </div>
    </div>
</x-app-layout>
