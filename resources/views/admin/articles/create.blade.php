{{-- resources/views/admin/articles/create.blade.php --}}
<x-app-layout :bodyClass="'bg-body-private-default'">
    @section('title', 'Nuovo Articolo - Admin PIMEL')

    <x-slot name="pageHeader">
        <x-layout.page-header title="Crea Nuovo Articolo" />
    </x-slot>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                    @include('admin.articles._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
