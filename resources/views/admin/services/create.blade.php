{{-- resources/views/admin/services/create.blade.php --}}
<x-app-layout>
  @section('title', 'Nuovo Servizio - Admin PIMEL')

  {{-- Utilizzo del Componente Breadcrumb --}}
  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <x-slot name="pageHeader">
    <x-layout.page-header title="Crea Nuovo Servizio" />
  </x-slot>

  <div class="container py-4">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        {{-- Il form per la creazione del servizio --}}
        <form
          action="{{ route('admin.services.store') }}"
          method="POST"
          class="needs-slug-generation"
        >
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
