@push('page-scripts')
  @vite('resources/js/admin-forms.js')
@endpush

<x-app-layout>
  @section('title', 'Nuovo Articolo')

  <x-slot name="pageHeader">
    <div class="container">
      <x-layout.page-header title="Nuovo Articolo">
        <a
          href="{{ route('admin.articles.index') }}"
          class="btn btn-secondary"
        >
          <span class="material-symbols-outlined fs-6 me-1 align-middle">
            arrow_back
          </span>
          Annulla
        </a>
      </x-layout.page-header>
    </div>
  </x-slot>

  <div class="container py-4">
    <div class="card shadow-sm">
      <div class="card-header">
        <h3 class="card-title mb-0">Crea Articolo</h3>
      </div>
      <div class="card-body">
        <form
          action="{{ route('admin.articles.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="needs-slug-generation"
          data-slug-source-field="title"
        >
          @csrf
          @include(
            'admin.articles._form',
            [
              'article' => $article,
              'rubrics' => $rubrics,
              'buttonText' => 'Crea Articolo',
            ]
          )
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
