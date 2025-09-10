<x-app-layout>
    @section('title', 'Modifica Servizio - Admin PIMEL')

    @isset($breadcrumbs)
        <div class="container pt-3">
            <x-admin.breadcrumb :items="$breadcrumbs" />
        </div>
    @endisset

    <x-slot name="pageHeader">
        <x-layout.page-header :title="'Modifica Servizio: ' . Str::limit($service->name, 40)" />
    </x-slot>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.services.update', $service) }}" method="POST">
                    @method('PUT')
                    @include('admin.services._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>