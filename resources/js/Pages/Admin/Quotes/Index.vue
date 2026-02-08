<template>
    <AdminLayout title="Cotizaciones">
        <form class="mb-6 grid gap-4 rounded-2xl bg-white p-6 shadow-sm md:grid-cols-4" @submit.prevent="applyFilters">
            <div>
                <label class="text-xs font-semibold uppercase text-slate-500">Estado</label>
                <select v-model="form.status" class="mt-2 w-full rounded-xl border-slate-200 bg-white/90 text-sm">
                    <option value="">Todos</option>
                    <option value="new">New</option>
                    <option value="seen">Seen</option>
                    <option value="quoted">Quoted</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase text-slate-500">Desde</label>
                <input v-model="form.from" type="date" class="mt-2 w-full rounded-xl border-slate-200 text-sm" />
            </div>
            <div>
                <label class="text-xs font-semibold uppercase text-slate-500">Hasta</label>
                <input v-model="form.to" type="date" class="mt-2 w-full rounded-xl border-slate-200 text-sm" />
            </div>
            <div class="flex items-end gap-3">
                <button class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-ls-indigo px-4 py-2 text-xs font-semibold text-white">
                    <IconFilter />
                    Filtrar
                </button>
                <a :href="exportUrl" class="rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700">Export CSV</a>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
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
                    <tr v-for="quote in quotes.data" :key="quote.id" class="border-t border-slate-100 hover:bg-slate-50 transition">
                        <td class="px-6 py-4">{{ quote.quote_number }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-ls-navy">{{ quote.name }}</p>
                            <p class="text-xs text-slate-500">{{ quote.email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase" :class="statusClass(quote.status)">
                                {{ quote.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">Q {{ formatMoney(quote.subtotal_estimate) }}</td>
                        <td class="px-6 py-4 text-right">
                            <Link :href="`/admin/quotes/${quote.id}`" class="inline-flex items-center gap-1 text-xs font-semibold text-ls-blue hover:text-ls-indigo">
                                <IconEye />
                                Ver detalle
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="quotes.links" />
    </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import Pagination from '../../Shared/Pagination.vue';
import IconFilter from '../../Shared/icons/IconFilter.vue';
import IconEye from '../../Shared/icons/IconEye.vue';

const props = defineProps({
    quotes: Object,
    filters: Object,
});

const form = useForm({
    status: props.filters?.status || '',
    from: props.filters?.from || '',
    to: props.filters?.to || '',
});

const applyFilters = () => {
    router.get('/admin/quotes', form.data(), { preserveState: true, replace: true });
};

const exportUrl = computed(() => {
    const params = new URLSearchParams(form.data()).toString();
    return `/admin/quotes/export${params ? `?${params}` : ''}`;
});

const formatMoney = (value) => Number(value ?? 0).toLocaleString('es-GT', { minimumFractionDigits: 2 });

const statusClass = (status) => {
    switch (status) {
        case 'new':
            return 'bg-blue-100 text-blue-700';
        case 'seen':
            return 'bg-amber-100 text-amber-700';
        case 'quoted':
            return 'bg-emerald-100 text-emerald-700';
        case 'closed':
            return 'bg-slate-200 text-slate-700';
        default:
            return 'bg-slate-100 text-slate-700';
    }
};
</script>
