@php
    $title = 'Carrito | GT Hobby';
@endphp

<x-layouts.site :title="$title">
    <section class="toy-shelf">
        <div class="mx-auto max-w-6xl px-6 py-12">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-ls-ink">Carrito GT</p>
                    <h1 class="mt-2 font-display text-3xl text-ls-navy">Tu vitrina personal</h1>
                    <p class="mt-2 text-sm text-slate-600">Revisa cantidades y solicita tu cotizacion.</p>
                </div>
                <a href="{{ route('catalog') }}" class="rounded-full border border-slate-200 px-5 py-2 text-xs font-semibold text-slate-700">Seguir comprando</a>
            </div>

            <x-flash />

            @if(count($items))
                <form method="POST" action="{{ route('cart.update') }}" class="admin-table mt-8">
                    @csrf
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Producto</th>
                                <th class="px-6 py-4">Precio</th>
                                <th class="px-6 py-4">Cantidad</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr class="border-t border-slate-100">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-ls-navy">{{ $item['name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4">Q {{ number_format($item['price'], 2, '.', ',') }}</td>
                                    <td class="px-6 py-4">
                                        <input type="number" min="0" name="items[{{ $item['product_id'] }}]" value="{{ $item['qty'] }}" class="w-20 rounded-lg border-slate-200 text-sm">
                                    </td>
                                    <td class="px-6 py-4">Q {{ number_format($item['price'] * $item['qty'], 2, '.', ',') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <button form="remove-{{ $item['product_id'] }}" class="text-xs font-semibold text-rose-500 hover:text-rose-600">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex flex-col items-end gap-4 border-t border-slate-100 px-6 py-4 md:flex-row md:justify-between">
                        <div class="w-full max-w-md">
                            <label class="text-xs uppercase text-slate-500">Notas para el pedido</label>
                            <textarea name="notes" rows="2" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 text-sm">{{ old('notes', $notes) }}</textarea>
                        </div>
                        <x-button type="submit" variant="light">Actualizar carrito</x-button>
                        <div class="text-right">
                            <p class="text-xs uppercase text-slate-500">Subtotal estimado</p>
                            <p class="text-xl font-semibold text-ls-navy">Q {{ number_format($subtotal, 2, '.', ',') }}</p>
                        </div>
                    </div>
                </form>
                @foreach($items as $item)
                    <form id="remove-{{ $item['product_id'] }}" method="POST" action="{{ route('cart.remove', $item['product_id']) }}" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('quote.create') }}" class="rounded-full bg-ls-indigo px-6 py-3 text-sm font-semibold text-white">Enviar cotizacion</a>
                </div>
            @else
                <div class="toy-panel mt-8 p-10 text-center text-slate-500">
                    Tu carrito esta vacio.
                </div>
            @endif
        </div>
    </section>
</x-layouts.site>
