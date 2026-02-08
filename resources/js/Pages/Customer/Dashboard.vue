<template>
    <CustomerLayout>
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="toy-panel p-6 lg:col-span-1">
                <div class="flex items-center gap-2 text-xs uppercase tracking-[0.3em] text-ls-ink">
                    <IconUser class="text-ls-indigo" />
                    Perfil
                </div>
                <h2 class="mt-3 font-display text-xl text-ls-navy">{{ user.name }}</h2>
                <p class="mt-2 text-sm text-slate-600">{{ user.email }}</p>
                <p class="text-sm text-slate-600">{{ user.phone }}</p>
                <Link href="/profile" class="mt-4 inline-flex text-sm font-semibold text-ls-indigo">Editar perfil</Link>
            </div>
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2">
                    <IconDoc class="text-ls-indigo" />
                    <h3 class="font-display text-xl text-ls-navy">Cotizaciones recientes</h3>
                </div>
                <div class="mt-4 space-y-4">
                    <div v-for="quote in quotes.data" :key="quote.id" class="toy-panel flex items-center justify-between px-6 py-4">
                        <div>
                            <p class="text-sm font-semibold text-ls-navy">{{ quote.quote_number }}</p>
                            <p class="text-xs text-slate-500">{{ formatDate(quote.created_at) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase" :class="statusClass(quote.status)">{{ quote.status }}</span>
                            <p class="mt-2 text-sm font-semibold text-ls-blue">Q {{ formatMoney(quote.subtotal_estimate) }}</p>
                        </div>
                    </div>
                    <div v-if="quotes.data.length === 0" class="toy-panel p-6">
                        <p class="text-sm text-slate-500">Aun no has enviado cotizaciones.</p>
                    </div>
                </div>
                <Pagination :links="quotes.links" />
            </div>
        </div>
    </CustomerLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import CustomerLayout from '../Layouts/CustomerLayout.vue';
import Pagination from '../Shared/Pagination.vue';
import IconDoc from '../Shared/icons/IconDoc.vue';
import IconUser from '../Shared/icons/IconUser.vue';

defineProps({
    user: Object,
    quotes: Object,
});

const formatMoney = (value) => Number(value ?? 0).toLocaleString('es-GT', { minimumFractionDigits: 2 });
const formatDate = (value) => new Date(value).toLocaleDateString('es-GT');

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
