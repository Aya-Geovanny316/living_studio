@php
    $title = 'Editar categoria';
@endphp

<x-layouts.admin :title="$title">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="admin-card p-8">
        @method('PUT')
        @include('admin.categories._form', ['button' => 'Guardar cambios'])
    </form>
</x-layouts.admin>
