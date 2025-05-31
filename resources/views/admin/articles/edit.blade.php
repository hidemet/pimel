{{-- resources/views/admin/articles/edit.blade.php --}}
<x-app-layout :bodyClass="'bg-body-private-default'">
    @section('title', 'Modifica Articolo: ' . $article->title . ' - Admin PIMEL')

    <x-slot name="pageHeader">
        <x-layout.page-header :title="'Modifica Articolo: ' . Str::limit($article->title, 40)" />
    </x-slot>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT') {{-- Metodo per l'update --}}
                    @include('admin.articles._form', ['article' => $article, 'rubrics' => $rubrics, 'authors' => $authors, 'articleStatuses' => $articleStatuses])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>