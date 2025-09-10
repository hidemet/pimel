{{-- resources/views/admin/articles/create.blade.php --}}
<x-app-layout>
    @section('title', 'Nuovo Articolo - Admin PIMEL')

    {{-- Utilizzo del Componente Breadcrumb --}}
    @isset($breadcrumbs)
        <div class="container pt-3">
            <x-admin.breadcrumb :items="$breadcrumbs" />
        </div>
    @endisset

    <x-slot name="pageHeader">
        <x-layout.page-header title="Crea Nuovo Articolo" />
    </x-slot>

    <div class="container py-4">
        {{-- Il form deve avere enctype="multipart/form-data" per gli upload di file --}}
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
            {{-- Inietta l'istanza di Article vuota, e le collezioni di rubriche e autori --}}
            @include('admin.articles._form', [
                'article' => new \App\Models\Article(['user_id' => Auth::id()]),
                'rubrics' => $rubrics,
                'authors' => $authors
            ])
        </form>
    </div>
</x-app-layout>