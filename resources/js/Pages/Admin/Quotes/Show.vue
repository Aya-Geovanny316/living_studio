<template>
    <AdminLayout :title="`Cotizacion ${quote.quote_number}`">
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="ls-card p-6 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase tracking-[0.3em] text-ls-blue">Cliente</p>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase" :class="statusClass(quote.status)">
                        {{ quote.status }}
                    </span>
                </div>
                <p class="mt-3 font-semibold text-ls-navy">{{ quote.name }}</p>
                <p class="text-sm text-slate-600">{{ quote.email }}</p>
                <p class="text-sm text-slate-600">{{ quote.phone }}</p>
                <p class="mt-3 text-xs text-slate-500">{{ quote.address }}</p>
                <div class="mt-4">
                    <form @submit.prevent="updateStatus" class="flex items-center gap-2">
                        <select v-model="statusForm.status" class="rounded-xl border-slate-200 text-sm">
                            <option value="new">New</option>
                            <option value="seen">Seen</option>
                            <option value="quoted">Quoted</option>
                            <option value="closed">Closed</option>
                        </select>
                        <button class="rounded-full bg-ls-blue px-4 py-2 text-xs font-semibold text-white">Actualizar</button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="ls-card p-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-ls-blue">Notas</p>
                    <p class="mt-3 text-sm text-slate-600">{{ quote.notes || 'Sin notas' }}</p>
                </div>
                <div class="ls-card p-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-ls-blue">Responder cotizacion</p>
                    <form @submit.prevent="reply" class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Mensaje al cliente</label>
                            <textarea v-model="replyForm.response_message" rows="4" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm"></textarea>
                            <p v-if="replyForm.errors.response_message" class="mt-1 text-xs text-rose-600">{{ replyForm.errors.response_message }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Total propuesto (opcional)</label>
                            <input v-model="replyForm.response_total_estimate" type="number" step="0.01" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
                        </div>
                        <button class="rounded-full bg-ls-indigo px-6 py-3 text-sm font-semibold text-white">Enviar respuesta</button>
                    </form>
                </div>
                <div class="ls-card p-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-ls-blue">Items</p>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <div v-for="item in quote.items" :key="item.id" class="flex items-center justify-between">
                            <span>{{ item.product_name_snapshot }} x{{ item.qty }}</span>
                            <span class="font-semibold text-slate-700">Q {{ formatMoney(item.line_total_estimate) }}</span>
                        </div>
                    </div>
                    <div class="mt-6 border-t border-slate-200 pt-4 text-right">
                        <p class="text-xs uppercase text-slate-500">Subtotal estimado</p>
                        <p class="text-xl font-semibold text-ls-navy">Q {{ formatMoney(quote.subtotal_estimate) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const props = defineProps({
    quote: Object,
});

const statusForm = useForm({
    status: props.quote.status,
});

const replyForm = useForm({
    response_message: props.quote.response_message || '',
    response_total_estimate: props.quote.response_total_estimate || '',
});

const updateStatus = () => {
    statusForm.patch(`/admin/quotes/${props.quote.id}/status`);
};

const reply = () => {
    replyForm.post(`/admin/quotes/${props.quote.id}/reply`);
};

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
