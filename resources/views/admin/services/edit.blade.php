{{-- resources/views/admin/services/edit.blade.php --}}
<x-app-layout>
  @section('title', 'Modifica Servizio: ' . Str::limit($service->name, 30) . ' - Admin PIMEL')

  {{-- Utilizzo del Componente Breadcrumb --}}
  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <x-slot name="pageHeader">
    <x-layout.page-header
      :title="'Modifica Servizio: ' . Str::limit($service->name, 40)"
    />
  </x-slot>

  <div class="container py-4">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        {{-- Il form per l'aggiornamento del servizio --}}
        <form
          action="{{ route('admin.services.update', $service) }}"
          method="POST"
        >
          @method('PUT')
          {{-- Usa il metodo PUT per gli update --}}
          {{-- Include il form con tutte le variabili necessarie --}}
          @include(
            'admin.services._form',
            [
              'service' => $service,
              'targetCategories' => $targetCategories,
            ]
          )
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
