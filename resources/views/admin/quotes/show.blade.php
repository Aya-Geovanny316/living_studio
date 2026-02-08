@php
    $title = 'Cotizacion ' . $quote->quote_number;
@endphp

<x-layouts.admin :title="$title">
    <div class="grid gap-8 lg:grid-cols-3">
        <x-card class="admin-card lg:col-span-1">
            <p class="admin-kicker text-ls-blue">Cliente</p>
            <p class="mt-3 font-semibold text-ls-navy">{{ $quote->name }}</p>
            <p class="text-sm text-slate-600">{{ $quote->email }}</p>
            <p class="text-sm text-slate-600">{{ $quote->phone }}</p>
            <p class="mt-3 text-xs text-slate-500">{{ $quote->address }}</p>
            <div class="mt-4">
                <form method="POST" action="{{ route('admin.quotes.status', $quote) }}" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="rounded-xl border-slate-200 bg-white/80 text-sm">
                        @foreach(['new' => 'New', 'seen' => 'Seen', 'quoted' => 'Quoted', 'closed' => 'Closed'] as $key => $label)
                            <option value="{{ $key }}" @selected($quote->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-button type="submit" variant="dark">Actualizar</x-button>
                </form>
            </div>
        </x-card>

        <div class="lg:col-span-2 space-y-6">
            <x-card class="admin-card">
                <p class="admin-kicker text-ls-blue">Notas</p>
                <p class="mt-3 text-sm text-slate-600">{{ $quote->notes ?? 'Sin notas' }}</p>
            </x-card>
            <x-card class="admin-card">
                <p class="admin-kicker text-ls-blue">Responder cotizacion</p>
                <x-form-errors />
                <form method="POST" action="{{ route('admin.quotes.reply', $quote) }}" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Mensaje al cliente</label>
                        <textarea name="response_message" rows="4" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm">{{ old('response_message', $quote->response_message) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Total propuesto (opcional)</label>
                        <input type="number" step="0.01" name="response_total_estimate" value="{{ old('response_total_estimate', $quote->response_total_estimate) }}" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm">
                    </div>
                    <x-button variant="dark" type="submit">Enviar respuesta</x-button>
                </form>
            </x-card>
            <x-card class="admin-card">
                <p class="admin-kicker text-ls-blue">Items</p>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    @foreach($quote->items as $item)
                        <div class="flex items-center justify-between">
                            <span>{{ $item->product_name_snapshot }} x{{ $item->qty }}</span>
                            <span class="font-semibold text-slate-700">Q {{ number_format($item->line_total_estimate, 2, '.', ',') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 border-t border-slate-200 pt-4 text-right">
                    <p class="text-xs uppercase text-slate-500">Subtotal estimado</p>
                    <p class="text-xl font-semibold text-ls-navy">Q {{ number_format($quote->subtotal_estimate, 2, '.', ',') }}</p>
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.admin>
