<template>
    <AdminLayout title="Editar categoria">
        <form class="rounded-2xl bg-white p-8 shadow-sm" @submit.prevent="submit">
            <CategoryForm :form="form" />
            <div class="mt-6">
                <button class="rounded-full bg-ls-indigo px-6 py-3 text-sm font-semibold text-white">Guardar cambios</button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import CategoryForm from './FormFields.vue';

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name,
    icon: props.category.icon ?? '',
    sort_order: props.category.sort_order ?? 0,
    is_active: Boolean(props.category.is_active),
});

const submit = () => {
    form.put(`/admin/categories/${props.category.id}`);
};
</script>
