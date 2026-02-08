@php
    $title = 'Dashboard';
@endphp

<x-layouts.admin :title="$title">
    <div class="grid gap-6 md:grid-cols-4">
        <x-card class="admin-card">
            <p class="admin-kicker">Cotizaciones nuevas</p>
            <p class="mt-2 text-3xl font-semibold text-ls-navy">{{ $metrics['quotes_new'] }}</p>
        </x-card>
        <x-card class="admin-card">
            <p class="admin-kicker">Productos activos</p>
            <p class="mt-2 text-3xl font-semibold text-ls-navy">{{ $metrics['products_active'] }}</p>
        </x-card>
        <x-card class="admin-card">
            <p class="admin-kicker">Categorias</p>
            <p class="mt-2 text-3xl font-semibold text-ls-navy">{{ $metrics['categories_active'] }}</p>
        </x-card>
        <x-card class="admin-card">
            <p class="admin-kicker">Promos activas</p>
            <p class="mt-2 text-3xl font-semibold text-ls-navy">{{ $metrics['promotions_active'] }}</p>
        </x-card>
    </div>

    <div class="mt-10">
        <h2 class="font-display text-xl text-ls-navy">Ultimas cotizaciones</h2>
        <div class="admin-table mt-4">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Numero</th>
                        <th class="px-6 py-3">Cliente</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestQuotes as $quote)
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-4">{{ $quote->quote_number }}</td>
                            <td class="px-6 py-4">{{ $quote->name }}</td>
                            <td class="px-6 py-4">{{ $quote->status }}</td>
                            <td class="px-6 py-4">Q {{ number_format($quote->subtotal_estimate, 2, '.', ',') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
