@php
    $title = 'Editar producto';
@endphp

<x-layouts.admin :title="$title">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="admin-card p-8">
        @method('PUT')
        @include('admin.products._form', ['button' => 'Guardar cambios'])
    </form>
</x-layouts.admin>
