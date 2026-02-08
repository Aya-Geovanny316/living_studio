<template>
    <AdminLayout title="Editar producto">
        <form class="rounded-2xl bg-white p-8 shadow-sm" @submit.prevent="submit">
            <FormFields :form="form" :categories="categories" />
            <div class="mt-6">
                <button class="rounded-full bg-ls-indigo px-6 py-3 text-sm font-semibold text-white">Guardar cambios</button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import FormFields from './FormFields.vue';

const props = defineProps({
    product: Object,
    categories: Array,
});

const form = useForm({
    _method: 'put',
    name: props.product.name,
    category_id: props.product.category_id,
    price_estimate: props.product.price_estimate,
    sku: props.product.sku ?? '',
    stock_status: props.product.stock_status ?? '',
    short_description: props.product.short_description ?? '',
    description: props.product.description ?? '',
    specs: props.product.specs
        ? Object.entries(props.product.specs).map(([key, value]) => ({ key, value }))
        : [{ key: '', value: '' }],
    image_position: props.product.image_position || 'center',
    existing_images: (props.product.images || []).map((img) =>
        typeof img === 'string' ? { url: img, position: props.product.image_position || 'center' } : { url: img.url, position: img.position || 'center' }
    ),
    new_images_meta: [],
    images: [],
    images_touched: false,
    featured: Boolean(props.product.featured),
    is_active: Boolean(props.product.is_active),
});

const submit = () => {
    form.post(`/admin/products/${props.product.id}`, {
        forceFormData: true,
    });
};
</script>
