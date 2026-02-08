@php
    $title = 'Promociones';
@endphp

<x-layouts.admin :title="$title">
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-slate-600">Banners estilo social para home.</p>
        <a href="{{ route('admin.promotions.create') }}" class="rounded-full bg-ls-indigo px-4 py-2 text-sm font-semibold text-white">Nueva promocion</a>
    </div>
    <div class="admin-table">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-3">Titulo</th>
                    <th class="px-6 py-3">Orden</th>
                    <th class="px-6 py-3">Activo</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($promotions as $promotion)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-ls-navy">{{ $promotion->title }}</p>
                            <p class="text-xs text-slate-500">{{ $promotion->subtitle }}</p>
                        </td>
                        <td class="px-6 py-4">{{ $promotion->sort_order }}</td>
                        <td class="px-6 py-4">{{ $promotion->is_active ? 'Si' : 'No' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.promotions.edit', $promotion) }}" class="text-xs font-semibold text-ls-blue">Editar</a>
                            <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}" class="inline">
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
    <div class="mt-6">{{ $promotions->links() }}</div>
</x-layouts.admin>
