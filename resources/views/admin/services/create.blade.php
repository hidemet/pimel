<x-app-layout>
  @section('title', 'Nuovo Servizio - Admin PIMEL')

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
        {{-- Aggiungiamo la classe per attivare lo script dello slug --}}
        <form
          action="{{ route('admin.services.store') }}"
          method="POST"
          class="needs-slug-generation"
        >
          @include('admin.services._form')
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
