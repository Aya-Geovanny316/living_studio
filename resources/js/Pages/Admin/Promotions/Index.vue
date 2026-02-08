<template>
    <AdminLayout title="Promociones">
        <div class="mb-6 flex items-center justify-between">
            <p class="text-sm text-slate-600">Banners estilo social para home.</p>
            <Link href="/admin/promotions/create" class="inline-flex items-center gap-2 rounded-full bg-ls-indigo px-4 py-2 text-sm font-semibold text-white">
                <IconPlus />
                Nueva promocion
            </Link>
        </div>
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Titulo</th>
                        <th class="px-6 py-3">Producto</th>
                        <th class="px-6 py-3">Descuento</th>
                        <th class="px-6 py-3">Orden</th>
                        <th class="px-6 py-3">Activo</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="promo in promotions.data" :key="promo.id" class="border-t border-slate-100 hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-ls-navy">{{ promo.title }}</p>
                            <p class="text-xs text-slate-500">{{ promo.subtitle }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600">
                            {{ promo.product_id ? 'Vinculado' : 'Sin producto' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600">
                            {{ promo.discount_percent ? promo.discount_percent + '%' : '-' }}
                        </td>
                        <td class="px-6 py-4">{{ promo.sort_order }}</td>
                        <td class="px-6 py-4">{{ promo.is_active ? 'Si' : 'No' }}</td>
                        <td class="px-6 py-4 text-right">
                            <Link :href="`/admin/promotions/${promo.id}/edit`" class="inline-flex items-center gap-1 text-xs font-semibold text-ls-blue hover:text-ls-indigo">
                                <IconEdit />
                                Editar
                            </Link>
                            <button class="ml-3 inline-flex items-center gap-1 text-xs font-semibold text-rose-500 hover:text-rose-600" @click="destroy(promo.id)">
                                <IconTrash />
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="promotions.links" />
    </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import Pagination from '../../Shared/Pagination.vue';
import IconPlus from '../../Shared/icons/IconPlus.vue';
import IconEdit from '../../Shared/icons/IconEdit.vue';
import IconTrash from '../../Shared/icons/IconTrash.vue';

defineProps({
    promotions: Object,
});

const destroy = (id) => {
    if (confirm('Eliminar promocion?')) {
        router.delete(`/admin/promotions/${id}`);
    }
};
</script>
