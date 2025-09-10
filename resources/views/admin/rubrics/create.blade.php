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
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <form
          action="{{ route('admin.rubrics.store') }}"
          method="POST"
          class="needs-slug-generation"
        >
          @include('admin.rubrics._form', ['rubric' => new Rubric()])
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
