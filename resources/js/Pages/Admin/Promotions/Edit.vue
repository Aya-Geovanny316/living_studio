<template>
    <AdminLayout title="Editar promocion">
        <form class="rounded-2xl bg-white p-8 shadow-sm" @submit.prevent="submit">
            <PromotionForm :form="form" :current-image="promotion.image_path" :products="products" />
            <div class="mt-6">
                <button class="rounded-full bg-ls-indigo px-6 py-3 text-sm font-semibold text-white">Guardar cambios</button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import PromotionForm from './FormFields.vue';

const props = defineProps({
    promotion: Object,
    products: Array,
});

const form = useForm({
    _method: 'put',
    title: props.promotion.title,
    subtitle: props.promotion.subtitle ?? '',
    link: props.promotion.link ?? '',
    product_id: props.promotion.product_id ?? '',
    discount_percent: props.promotion.discount_percent ?? '',
    type: props.promotion.type ?? 'promo',
    sort_order: props.promotion.sort_order ?? 0,
    is_active: Boolean(props.promotion.is_active),
    image: null,
});

const submit = () => {
    form.post(`/admin/promotions/${props.promotion.id}`, {
        forceFormData: true,
    });
};
</script>
