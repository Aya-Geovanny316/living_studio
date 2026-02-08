<template>
    <AdminLayout title="Categorias">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-slate-600">Organiza el catalogo por soluciones.</p>
            <div class="flex flex-wrap items-center gap-3">
                <button
                    class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold text-rose-600 disabled:opacity-50"
                    :disabled="selectedIds.length === 0"
                    @click="bulkDestroy"
                >
                    <IconTrash />
                    Eliminar seleccionadas
                </button>
                <Link href="/admin/categories/create" class="inline-flex items-center gap-2 rounded-full bg-ls-indigo px-4 py-2 text-sm font-semibold text-white">
                    <IconPlus />
                    Nueva categoria
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
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Slug</th>
                        <th class="px-6 py-3">Orden</th>
                        <th class="px-6 py-3">Activo</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="category in categories.data" :key="category.id" class="border-t border-slate-100 hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" v-model="selectedIds" :value="category.id" />
                        </td>
                        <td class="px-6 py-4 font-semibold text-ls-navy">{{ category.name }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ category.slug }}</td>
                        <td class="px-6 py-4">{{ category.sort_order }}</td>
                        <td class="px-6 py-4">{{ category.is_active ? 'Si' : 'No' }}</td>
                        <td class="px-6 py-4 text-right">
                            <Link :href="`/admin/categories/${category.id}/edit`" class="inline-flex items-center gap-1 text-xs font-semibold text-ls-blue hover:text-ls-indigo">
                                <IconEdit />
                                Editar
                            </Link>
                            <button class="ml-3 inline-flex items-center gap-1 text-xs font-semibold text-rose-500 hover:text-rose-600" @click="destroy(category.id)">
                                <IconTrash />
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination :links="categories.links" />
    </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import Pagination from '../../Shared/Pagination.vue';
import IconPlus from '../../Shared/icons/IconPlus.vue';
import IconEdit from '../../Shared/icons/IconEdit.vue';
import IconTrash from '../../Shared/icons/IconTrash.vue';

const props = defineProps({
    categories: Object,
});

const destroy = (id) => {
    if (confirm('Eliminar categoria?')) {
        router.delete(`/admin/categories/${id}`);
    }
};

const selectedIds = ref([]);
const allSelected = computed(() => {
    const ids = (props.categories?.data ?? []).map((item) => item.id);
    return ids.length > 0 && ids.every((id) => selectedIds.value.includes(id));
});

const toggleAll = (event) => {
    if (event.target.checked) {
        selectedIds.value = (props.categories?.data ?? []).map((item) => item.id);
        return;
    }
    selectedIds.value = [];
};

const bulkDestroy = () => {
    if (!selectedIds.value.length) {
        return;
    }
    if (confirm(`Eliminar ${selectedIds.value.length} categorias?`)) {
        router.delete('/admin/categories/bulk', {
            data: { ids: selectedIds.value },
            preserveScroll: true,
            onSuccess: () => {
                selectedIds.value = [];
            },
        });
    }
};
</script>
