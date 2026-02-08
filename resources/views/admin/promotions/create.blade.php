@php
    $title = 'Nueva promocion';
@endphp

<x-layouts.admin :title="$title">
    <form method="POST" action="{{ route('admin.promotions.store') }}" enctype="multipart/form-data" class="admin-card p-8">
        @include('admin.promotions._form', ['button' => 'Crear promocion'])
    </form>
</x-layouts.admin>
