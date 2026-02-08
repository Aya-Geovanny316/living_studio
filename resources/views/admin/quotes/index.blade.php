@php
    $title = 'Cotizaciones';
@endphp

<x-layouts.admin :title="$title">
    <form method="GET" action="{{ route('admin.quotes.index') }}" class="admin-card mb-6 grid gap-4 p-6 md:grid-cols-4">
        <div>
            <label class="text-xs font-semibold uppercase text-slate-500">Estado</label>
            <select name="status" class="mt-2 w-full rounded-xl border-slate-200 bg-white/90 text-sm">
                <option value="">Todos</option>
                @foreach(['new' => 'New', 'seen' => 'Seen', 'quoted' => 'Quoted', 'closed' => 'Closed'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase text-slate-500">Desde</label>
            <input type="date" name="from" value="{{ request('from') }}" class="mt-2 w-full rounded-xl border-slate-200 text-sm">
        </div>
        <div>
            <label class="text-xs font-semibold uppercase text-slate-500">Hasta</label>
            <input type="date" name="to" value="{{ request('to') }}" class="mt-2 w-full rounded-xl border-slate-200 text-sm">
        </div>
        <div class="flex items-end gap-3">
            <x-button type="submit" variant="dark" class="w-full">Filtrar</x-button>
            <a href="{{ route('admin.quotes.export', request()->query()) }}" class="rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700">Export CSV</a>
        </div>
    </form>

    <div class="admin-table">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-3">Numero</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotes as $quote)
                    <tr class="border-t border-slate-100">
                        <td class="px-6 py-4">{{ $quote->quote_number }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-ls-navy">{{ $quote->name }}</p>
                            <p class="text-xs text-slate-500">{{ $quote->email }}</p>
                        </td>
                        <td class="px-6 py-4">{{ $quote->status }}</td>
                        <td class="px-6 py-4">Q {{ number_format($quote->subtotal_estimate, 2, '.', ',') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.quotes.show', $quote) }}" class="text-xs font-semibold text-ls-blue">Ver detalle</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $quotes->links() }}</div>
</x-layouts.admin>
