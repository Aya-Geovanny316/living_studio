<template>
    <AdminLayout title="Dashboard">
        <div class="grid gap-6 md:grid-cols-4">
            <div class="ls-card p-6">
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase text-slate-500">Cotizaciones nuevas</p>
                    <span class="rounded-full bg-blue-100 p-2 text-blue-700">
                        <IconDoc />
                    </span>
                </div>
                <p class="mt-2 text-3xl font-semibold text-ls-navy">{{ metrics.quotes_new }}</p>
            </div>
            <div class="ls-card p-6">
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase text-slate-500">Productos activos</p>
                    <span class="rounded-full bg-indigo-100 p-2 text-indigo-700">
                        <IconBox />
                    </span>
                </div>
                <p class="mt-2 text-3xl font-semibold text-ls-navy">{{ metrics.products_active }}</p>
            </div>
            <div class="ls-card p-6">
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase text-slate-500">Categorias</p>
                    <span class="rounded-full bg-amber-100 p-2 text-amber-700">
                        <IconGrid />
                    </span>
                </div>
                <p class="mt-2 text-3xl font-semibold text-ls-navy">{{ metrics.categories_active }}</p>
            </div>
            <div class="ls-card p-6">
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase text-slate-500">Promos activas</p>
                    <span class="rounded-full bg-emerald-100 p-2 text-emerald-700">
                        <IconTag />
                    </span>
                </div>
                <p class="mt-2 text-3xl font-semibold text-ls-navy">{{ metrics.promotions_active }}</p>
            </div>
        </div>

        <div class="mt-10">
            <h2 class="font-display text-xl text-ls-navy">Ultimas cotizaciones</h2>
            <div class="mt-4 overflow-hidden rounded-2xl bg-white shadow-sm">
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
                        <tr v-for="quote in latestQuotes" :key="quote.id" class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="px-6 py-4">{{ quote.quote_number }}</td>
                            <td class="px-6 py-4">{{ quote.name }}</td>
                            <td class="px-6 py-4">{{ quote.status }}</td>
                            <td class="px-6 py-4">Q {{ formatMoney(quote.subtotal_estimate) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '../Layouts/AdminLayout.vue';
import IconDoc from '../Shared/icons/IconDoc.vue';
import IconBox from '../Shared/icons/IconBox.vue';
import IconGrid from '../Shared/icons/IconGrid.vue';
import IconTag from '../Shared/icons/IconTag.vue';

defineProps({
    metrics: Object,
    latestQuotes: Array,
});

const formatMoney = (value) => Number(value ?? 0).toLocaleString('es-GT', { minimumFractionDigits: 2 });
</script>
