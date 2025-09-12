@push('page-scripts')
  @vite('resources/js/admin-forms.js')
@endpush

<x-app-layout>
  @section('title', 'Modifica Articolo: ' . Str::limit($article->title, 30) . ' - Admin PIMEL')

  {{-- Utilizzo del Componente Breadcrumb --}}
  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <x-slot name="pageHeader">
    <x-layout.page-header
      :title="'Modifica Articolo: ' . Str::limit($article->title, 40)"
    />
  </x-slot>

  <div class="container py-4">
    <div class="card shadow-sm bg-primary-subtle">
      <div class="card-body p-4">
        <form
          action="{{ route('admin.articles.update', $article) }}"
          method="POST"
          enctype="multipart/form-data"
          class="needs-slug-generation"
          data-slug-source-field="title"
        >
          @method('PUT')
          @include(
            'admin.articles._form',
            [
              'article' => $article,
              'rubrics' => $rubrics,
            ]
          )
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
