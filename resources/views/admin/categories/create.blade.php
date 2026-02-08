@php
    $title = 'Nueva categoria';
@endphp

<x-layouts.admin :title="$title">
    <form method="POST" action="{{ route('admin.categories.store') }}" class="admin-card p-8">
        @include('admin.categories._form', ['button' => 'Crear categoria'])
    </form>
</x-layouts.admin>
