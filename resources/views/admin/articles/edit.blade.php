{{-- resources/views/admin/articles/edit.blade.php --}}
<x-app-layout>
    @section('title', 'Modifica Articolo: ' . Str::limit($article->title, 30) . ' - Admin PIMEL')

    {{-- Utilizzo del Componente Breadcrumb --}}
    @isset($breadcrumbs)
        <div class="container pt-3">
            <x-admin.breadcrumb :items="$breadcrumbs" />
        </div>
    @endisset

    <x-slot name="pageHeader">
        <x-layout.page-header :title="'Modifica Articolo: ' . Str::limit($article->title, 40)" />
    </x-slot>

    <div class="container py-4">
        {{-- Il form deve avere enctype="multipart/form-data" per gli upload di file --}}
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
            @method('PUT') {{-- Usa il metodo PUT per gli update --}}
            {{-- Inietta l'istanza di Article esistente e le collezioni di rubriche e autori --}}
            @include('admin.articles._form', [
                'article' => $article,
                'rubrics' => $rubrics,
                'authors' => $authors
            ])
        </form>
    </div>
</x-app-layout>