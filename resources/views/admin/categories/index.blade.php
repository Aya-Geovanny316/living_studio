@php
    $title = 'Categorias';
@endphp

<x-layouts.admin :title="$title">
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-600">Organiza el catalogo por soluciones.</p>
        <a href="{{ route('admin.categories.create') }}" class="rounded-full bg-ls-indigo px-4 py-2 text-sm font-semibold text-white">Nueva categoria</a>
    </div>
    <div class="admin-table">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Slug</th>
                    <th class="px-6 py-3">Orden</th>
                    <th class="px-6 py-3">Activo</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4 font-semibold text-ls-navy">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $category->slug }}</td>
                        <td class="px-6 py-4">{{ $category->sort_order }}</td>
                        <td class="px-6 py-4">{{ $category->is_active ? 'Si' : 'No' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs font-semibold text-ls-blue">Editar</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="ml-3 text-xs font-semibold text-rose-500">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $categories->links() }}</div>
</x-layouts.admin>
