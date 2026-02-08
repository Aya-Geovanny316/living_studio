@php
    $title = 'Cotizacion | GT Hobby';
@endphp

<x-layouts.site :title="$title">
    <section class="toy-shelf">
        <div class="mx-auto max-w-6xl px-6 py-12">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-ls-ink">Solicitud de cotizacion</p>
                    <h1 class="mt-2 font-display text-3xl text-ls-navy">Prepara tu pedido de coleccion</h1>
                    <p class="mt-2 text-sm text-slate-600">Te respondemos con disponibilidad y tiempos.</p>
                </div>
                <a href="{{ route('cart.index') }}" class="rounded-full border border-slate-200 px-5 py-2 text-xs font-semibold text-slate-700">Volver al carrito</a>
            </div>

            <x-flash />
            <x-form-errors />

            <div class="mt-8 grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 toy-panel p-8">
                    <form method="POST" action="{{ route('quote.store') }}" class="space-y-6">
                        @csrf
                        <div class="grid gap-6 md:grid-cols-2">
                            <x-input name="name" label="Nombre" value="{{ old('name') }}" />
                            <x-input name="email" type="email" label="Email" value="{{ old('email') }}" />
                            <x-input name="phone" label="Telefono" value="{{ old('phone') }}" />
                            <x-input name="address" label="Direccion (opcional)" value="{{ old('address') }}" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Notas</label>
                            <textarea name="notes" rows="4" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm">{{ old('notes', $notes) }}</textarea>
                        </div>
                        <x-button variant="dark">Enviar cotizacion</x-button>
                    </form>
                </div>
                <div class="toy-panel p-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-ls-ink">Resumen</p>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        @foreach($items as $item)
                            <div class="flex items-center justify-between">
                                <span>{{ $item['name'] }} x{{ $item['qty'] }}</span>
                                <span class="font-semibold text-slate-700">Q {{ number_format($item['price'] * $item['qty'], 2, '.', ',') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 border-t border-slate-200 pt-4 text-right">
                        <p class="text-xs uppercase text-slate-500">Total estimado</p>
                        <p class="text-xl font-semibold text-ls-navy">Q {{ number_format($subtotal, 2, '.', ',') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.site>
