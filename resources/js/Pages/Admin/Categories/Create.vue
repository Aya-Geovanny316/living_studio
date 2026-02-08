<template>
    <AdminLayout title="Nueva categoria">
        <form class="rounded-2xl bg-white p-8 shadow-sm" @submit.prevent="submit">
            <CategoryForm :form="form" />
            <div class="mt-6">
                <button class="rounded-full bg-ls-indigo px-6 py-3 text-sm font-semibold text-white">Crear categoria</button>
            </div>
        </form>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import CategoryForm from './FormFields.vue';

const form = useForm({
    name: '',
    icon: '',
    sort_order: 0,
    is_active: true,
});

const submit = () => {
    form.post('/admin/categories');
};
</script>
