@php
    $title = 'Productos';
@endphp

<x-layouts.admin :title="$title">
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-600">Gestiona el catalogo completo.</p>
        <a href="{{ route('admin.products.create') }}" class="rounded-full bg-ls-indigo px-4 py-2 text-sm font-semibold text-white">Nuevo producto</a>
    </div>
    <div class="admin-table">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-3">Producto</th>
                    <th class="px-6 py-3">Categoria</th>
                    <th class="px-6 py-3">Precio</th>
                    <th class="px-6 py-3">Activo</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-ls-navy">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500">{{ $product->slug }}</p>
                        </td>
                        <td class="px-6 py-4">{{ $product->category?->name }}</td>
                        <td class="px-6 py-4">Q {{ number_format($product->price_estimate, 2, '.', ',') }}</td>
                        <td class="px-6 py-4">{{ $product->is_active ? 'Si' : 'No' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-xs font-semibold text-ls-blue">Editar</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline">
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
    <div class="mt-6">{{ $products->links() }}</div>
</x-layouts.admin>
