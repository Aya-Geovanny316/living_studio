@php
    $title = 'Editar promocion';
@endphp

<x-layouts.admin :title="$title">
    <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}" enctype="multipart/form-data" class="admin-card p-8">
        @method('PUT')
        @include('admin.promotions._form', ['button' => 'Guardar cambios'])
    </form>
</x-layouts.admin>
