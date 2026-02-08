<template>
    <AdminLayout title="Destacados">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm text-slate-600">Selecciona los productos destacados que apareceran en el inicio.</p>
                <p class="mt-2 text-xs text-slate-500">Marcados: {{ selectedIds.length }} de {{ products.length }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <input v-model="search" type="text" placeholder="Buscar producto..." class="rounded-full border border-slate-200 px-4 py-2 text-xs" />
                <button
                    class="inline-flex items-center gap-2 rounded-full bg-ls-indigo px-4 py-2 text-xs font-semibold text-white"
                    @click="save"
                >
                    Guardar destacados
                </button>
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
                        <th class="px-6 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in filteredProducts" :key="product.id" class="border-t border-slate-100 hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" v-model="selectedIds" :value="product.id" />
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-ls-navy">{{ product.name }}</p>
                            <p class="text-xs text-slate-500">{{ product.slug }}</p>
                        </td>
                        <td class="px-6 py-4">{{ product.category?.name || '-' }}</td>
                        <td class="px-6 py-4">
                            <span v-if="selectedIds.includes(product.id)" class="text-xs font-semibold text-emerald-600">Destacado</span>
                            <span v-else class="text-xs text-slate-500">Normal</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const props = defineProps({
    products: Array,
});

const search = ref('');
const selectedIds = ref((props.products || []).filter((product) => product.featured).map((product) => product.id));

const filteredProducts = computed(() => {
    const term = search.value.trim().toLowerCase();
    if (!term) {
        return props.products || [];
    }
    return (props.products || []).filter((product) => {
        return product.name?.toLowerCase().includes(term) || product.slug?.toLowerCase().includes(term);
    });
});

const allSelected = computed(() => {
    const ids = filteredProducts.value.map((item) => item.id);
    return ids.length > 0 && ids.every((id) => selectedIds.value.includes(id));
});

const toggleAll = (event) => {
    if (event.target.checked) {
        const ids = filteredProducts.value.map((item) => item.id);
        const merged = new Set([...selectedIds.value, ...ids]);
        selectedIds.value = Array.from(merged);
        return;
    }
    const removeIds = new Set(filteredProducts.value.map((item) => item.id));
    selectedIds.value = selectedIds.value.filter((id) => !removeIds.has(id));
};

const save = () => {
    router.post('/admin/featured', { featured_ids: selectedIds.value }, { preserveScroll: true });
};
</script>
