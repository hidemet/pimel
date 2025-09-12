@push('page-scripts')
  @vite('resources/js/admin-forms.js')
@endpush

<x-app-layout>
  @section('title', 'Modifica Rubrica: ' . $rubric->name . ' - Admin PIMEL')

  @php
    $breadcrumbs = [
      ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
      ['label' => 'Gestione Rubriche', 'url' => route('admin.rubrics.index')],
      ['label' => 'Modifica: ' . Str::limit($rubric->name, 30)],
    ];
  @endphp

  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <x-slot name="pageHeader">
    <x-layout.page-header
      :title="'Modifica Rubrica: ' . Str::limit($rubric->name, 40)"
    />
  </x-slot>

  <div class="container py-4">
    <div class="card shadow-sm bg-primary-subtle">
      <div class="card-body p-4">
        <form
          action="{{ route('admin.rubrics.update', $rubric) }}"
          method="POST"
          class="needs-slug-generation"
          data-slug-source-field="name"
        >
          @method('PUT')
          @include('admin.rubrics._form', ['rubric' => $rubric])
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
