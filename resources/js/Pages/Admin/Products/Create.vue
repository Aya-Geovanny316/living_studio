<template>
    <AdminLayout title="Nuevo producto">
        <form class="rounded-2xl bg-white p-8 shadow-sm" @submit.prevent="submit">
            <div v-if="form.hasErrors" class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <p class="font-semibold">No se pudo guardar el producto.</p>
                <ul class="mt-2 list-disc pl-4">
                    <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                </ul>
            </div>
            <FormFields :form="form" :categories="categories" />
            <div class="mt-6">
                <button class="rounded-full bg-ls-indigo px-6 py-3 text-sm font-semibold text-white">Crear producto</button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import FormFields from './FormFields.vue';

const props = defineProps({
    categories: Array,
});

const form = useForm({
    name: '',
    category_id: props.categories[0]?.id ?? '',
    price_estimate: '',
    sku: '',
    stock_status: '',
    short_description: '',
    description: '',
    specs: [{ key: '', value: '' }],
    image_position: 'center',
    existing_images: [],
    new_images_meta: [],
    images: [],
    images_touched: false,
    featured: false,
    is_active: true,
});

const submit = () => {
    form.post('/admin/products', {
        forceFormData: true,
    });
};
</script>
