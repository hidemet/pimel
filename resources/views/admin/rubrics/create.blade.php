@push('page-scripts')
  @vite('resources/js/admin-forms.js')
@endpush

<x-app-layout>
  @section('title', 'Nuova Rubrica - Admin PIMEL')

  {{-- Utilizzo del Componente Breadcrumb --}}
  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <x-slot name="pageHeader">
    <x-layout.page-header title="Crea Nuova Rubrica" />
  </x-slot>

  <div class="container py-4">
    <div class="card shadow-sm bg-primary-subtle">
      <div class="card-body p-4">
        <form
          action="{{ route('admin.rubrics.store') }}"
          method="POST"
          class="needs-slug-generation"
          data-slug-source-field="name"
        >
          @include('admin.rubrics._form', ['rubric' => $rubric])
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
