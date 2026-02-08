@php
    $title = 'Nuevo producto';
@endphp

<x-layouts.admin :title="$title">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="admin-card p-8">
        @include('admin.products._form', ['button' => 'Crear producto'])
    </form>
</x-layouts.admin>
