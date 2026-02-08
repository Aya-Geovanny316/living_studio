<template>
    <AdminLayout title="Productos">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-600">Gestiona el catalogo completo.</p>
            <div class="flex flex-wrap items-center gap-3">
                <button
                    class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold text-rose-600 disabled:opacity-50"
                    :disabled="selectedIds.length === 0"
                    @click="bulkDestroy"
                >
                    <IconTrash />
                    Eliminar seleccionados
                </button>
                <Link href="/admin/products/import" class="inline-flex items-center gap-2 rounded-full border border-ls-indigo px-4 py-2 text-xs font-semibold text-ls-indigo">
                    <IconBox />
                    Carga masiva
                </Link>
                <Link href="/admin/products/create" class="inline-flex items-center gap-2 rounded-full bg-ls-indigo px-4 py-2 text-sm font-semibold text-white">
                    <IconPlus />
                    Nuevo producto
                </Link>
            </div>
        </div>
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-3">
                            <input type="checkbox" :checked="allSelected" @change="toggleAll($event)" />
                        </th>
                        <th class="px-6 py-3">Producto</th>
                        <th class="px-6 py-3">Categoria</th>
                        <th class="px-6 py-3">Precio</th>
                        <th class="px-6 py-3">Activo</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products.data" :key="product.id" class="border-t border-slate-100 hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" v-model="selectedIds" :value="product.id" />
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-ls-navy">{{ product.name }}</p>
                            <p class="text-xs text-slate-500">{{ product.slug }}</p>
                        </td>
                        <td class="px-6 py-4">{{ product.category?.name }}</td>
                        <td class="px-6 py-4">Q {{ formatMoney(product.price_estimate) }}</td>
                        <td class="px-6 py-4">{{ product.is_active ? 'Si' : 'No' }}</td>
                        <td class="px-6 py-4 text-right">
                            <Link :href="`/admin/products/${product.id}/edit`" class="inline-flex items-center gap-1 text-xs font-semibold text-ls-blue hover:text-ls-indigo">
                                <IconEdit />
                                Editar
                            </Link>
                            <button class="ml-3 inline-flex items-center gap-1 text-xs font-semibold text-rose-500 hover:text-rose-600" @click="destroy(product.id)">
                                <IconTrash />
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="products.links" />
    </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import Pagination from '../../Shared/Pagination.vue';
import IconPlus from '../../Shared/icons/IconPlus.vue';
import IconBox from '../../Shared/icons/IconBox.vue';
import IconEdit from '../../Shared/icons/IconEdit.vue';
import IconTrash from '../../Shared/icons/IconTrash.vue';

const props = defineProps({
    products: Object,
});

const selectedIds = ref([]);
const allSelected = computed(() => {
    const ids = (props.products?.data ?? []).map((item) => item.id);
    return ids.length > 0 && ids.every((id) => selectedIds.value.includes(id));
});

const formatMoney = (value) => Number(value ?? 0).toLocaleString('es-GT', { minimumFractionDigits: 2 });

const destroy = (id) => {
    if (confirm('Eliminar producto?')) {
        router.delete(`/admin/products/${id}`);
    }
};

const toggleAll = (event) => {
    if (event.target.checked) {
        selectedIds.value = (props.products?.data ?? []).map((item) => item.id);
        return;
    }
    selectedIds.value = [];
};

const bulkDestroy = () => {
    if (!selectedIds.value.length) {
        return;
    }
    if (confirm(`Eliminar ${selectedIds.value.length} productos?`)) {
        router.delete('/admin/products/bulk', {
            data: { ids: selectedIds.value },
            preserveScroll: true,
            onSuccess: () => {
                selectedIds.value = [];
            },
        });
    }
};
</script>
