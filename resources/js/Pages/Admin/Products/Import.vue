<template>
    <AdminLayout title="Carga masiva de productos">
        <div class="grid gap-6 lg:grid-cols-[1.3fr_1fr]">
            <div class="rounded-2xl bg-white p-8 shadow-sm">
                <h2 class="font-display text-lg text-ls-navy">Sube tu archivo Excel</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Descarga la plantilla, completala y luego subela aqui.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <a href="/admin/products/import/template" class="inline-flex items-center gap-2 rounded-full border border-ls-indigo px-4 py-2 text-xs font-semibold text-ls-indigo">
                        Descargar plantilla
                    </a>
                    <Link href="/admin/products" class="text-xs font-semibold text-slate-500 hover:text-ls-indigo">
                        Volver a productos
                    </Link>
                </div>
                <form class="mt-6 space-y-4" @submit.prevent="submit">
                    <div>
                        <label class="text-xs font-semibold uppercase text-slate-500">Archivo Excel (.xlsx)</label>
                        <input
                            type="file"
                            accept=".xlsx"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                            @change="onFileChange"
                        />
                        <p class="mt-2 text-xs text-slate-500">Tamano maximo: 10MB.</p>
                    </div>
                    <button class="rounded-full bg-ls-indigo px-6 py-3 text-sm font-semibold text-white">
                        Importar productos
                    </button>
                </form>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-600 shadow-sm">
                <h3 class="font-semibold text-ls-navy">Columnas disponibles</h3>
                <ul class="mt-3 space-y-2 text-xs">
                    <li><span class="font-semibold text-slate-700">Categoria</span> (requerido)</li>
                    <li><span class="font-semibold text-slate-700">SKU</span> (requerido)</li>
                    <li><span class="font-semibold text-slate-700">Nombre</span> (requerido)</li>
                    <li><span class="font-semibold text-slate-700">Descripcion</span></li>
                    <li><span class="font-semibold text-slate-700">Precio (Q)</span> (requerido)</li>
                </ul>
                <div class="mt-4 rounded-xl bg-slate-50 p-4 text-xs text-slate-500">
                    Las categorias nuevas se crean automaticamente si no existen.
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const form = useForm({
    file: null,
});

const onFileChange = (event) => {
    form.file = event.target.files[0] ?? null;
};

const submit = () => {
    form.post('/admin/products/import', {
        forceFormData: true,
    });
};
</script>
