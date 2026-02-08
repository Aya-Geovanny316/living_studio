<template>
    <AdminLayout title="Nueva promocion">
        <form class="rounded-2xl bg-white p-8 shadow-sm" @submit.prevent="submit">
            <PromotionForm :form="form" :products="products" />
            <div class="mt-6">
                <button class="rounded-full bg-ls-indigo px-6 py-3 text-sm font-semibold text-white">Crear promocion</button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import PromotionForm from './FormFields.vue';

const form = useForm({
    title: '',
    subtitle: '',
    link: '',
    product_id: '',
    discount_percent: '',
    type: 'promo',
    sort_order: 0,
    is_active: true,
    image: null,
});

const submit = () => {
    form.post('/admin/promotions', {
        forceFormData: true,
    });
};

defineProps({
    products: Array,
});
</script>
