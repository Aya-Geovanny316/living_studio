<template>
    <PublicLayout>
        <section class="toy-shelf">
            <div class="mx-auto max-w-3xl px-6 py-16">
                <div class="toy-panel p-10 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-ls-indigo text-white">
                        <span class="text-2xl">OK</span>
                    </div>
                    <h1 class="font-display text-2xl text-ls-navy">Cotizacion enviada</h1>
                    <p class="mt-3 text-sm text-slate-600">
                        Gracias por elegir GT Hobby. Tu numero de cotizacion es:
                    </p>
                    <p class="mt-4 text-xl font-semibold text-ls-blue">{{ quote.quote_number }}</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <Link href="/catalogo" class="rounded-full bg-ls-indigo px-6 py-3 text-sm font-semibold text-white">Volver al catalogo</Link>
                        <Link href="/" class="rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700">Inicio</Link>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';

defineProps({
    quote: Object,
});
</script>
