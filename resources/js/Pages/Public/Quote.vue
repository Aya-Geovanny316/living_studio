<template>
    <PublicLayout>
        <div class="page-dark">
            <section class="section">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-white/70">Solicitud de cotizacion</p>
                            <h1 class="mt-2 font-display text-3xl text-white">Prepara tu pedido de coleccion</h1>
                            <p class="mt-2 text-sm text-white/70">Te respondemos con disponibilidad y tiempos.</p>
                        </div>
                        <Link href="/carrito" class="rounded-full border border-white/10 px-5 py-2 text-xs font-semibold text-white/80 hover:text-white">Volver al carrito</Link>
                    </div>

                    <FlashMessages />

                    <div v-if="hasErrors" class="mt-6 rounded-2xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm text-rose-200">
                        <p class="font-semibold">Revisa los campos marcados.</p>
                    </div>

                    <div class="mt-8 grid gap-8 lg:grid-cols-3">
                        <div class="lg:col-span-2 card-dark p-8">
                            <form method="POST" action="/cotizacion" class="space-y-6">
                                <input type="hidden" name="_token" :value="csrf" />
                                <div class="grid gap-6 md:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-semibold text-white/80">Nombre</label>
                                        <input name="name" type="text" class="mt-2 w-full rounded-xl border border-white/10 bg-[var(--bg-700)] text-sm text-white placeholder:text-white/40" :value="old?.name" />
                                        <p v-if="errors?.name" class="mt-1 text-xs text-rose-400">{{ errors.name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-white/80">Email</label>
                                        <input name="email" type="email" class="mt-2 w-full rounded-xl border border-white/10 bg-[var(--bg-700)] text-sm text-white placeholder:text-white/40" :value="old?.email" />
                                        <p v-if="errors?.email" class="mt-1 text-xs text-rose-400">{{ errors.email }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-white/80">Telefono</label>
                                        <input name="phone" type="text" class="mt-2 w-full rounded-xl border border-white/10 bg-[var(--bg-700)] text-sm text-white placeholder:text-white/40" :value="old?.phone" />
                                        <p v-if="errors?.phone" class="mt-1 text-xs text-rose-400">{{ errors.phone }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-white/80">Direccion (opcional)</label>
                                        <input name="address" type="text" class="mt-2 w-full rounded-xl border border-white/10 bg-[var(--bg-700)] text-sm text-white placeholder:text-white/40" :value="old?.address" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-white/80">Notas</label>
                                    <textarea name="notes" rows="4" class="mt-2 w-full rounded-xl border border-white/10 bg-[var(--bg-700)] px-4 py-3 text-sm text-white placeholder:text-white/40">{{ old?.notes || notes }}</textarea>
                                </div>
                                <button type="submit" class="button-primary">Enviar cotizacion</button>
                            </form>
                        </div>
                        <div class="card-dark p-6">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/70">Resumen</p>
                            <div class="mt-4 space-y-3 text-sm text-white/70">
                                <div v-for="item in items" :key="item.product_id" class="flex items-center justify-between">
                                    <span>{{ item.name }} x{{ item.qty }}</span>
                                    <span class="font-semibold text-white">Q {{ formatMoney(item.price * item.qty) }}</span>
                                </div>
                            </div>
                            <div class="mt-6 border-t border-white/10 pt-4 text-right">
                                <p class="text-xs uppercase text-white/50">Total estimado</p>
                                <p class="text-xl font-semibold text-white">Q {{ formatMoney(subtotal) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';
import FlashMessages from '../Shared/FlashMessages.vue';

defineProps({
    items: Array,
    subtotal: Number,
    notes: String,
    old: Object,
});

const page = usePage();
const errors = computed(() => page.props.errors || {});
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const formatMoney = (value) => Number(value ?? 0).toLocaleString('es-GT', { minimumFractionDigits: 2 });
</script>
