<template>
    <PublicLayout>
        <div class="page-dark">
            <section class="section">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-white/70">Carrito GT</p>
                            <h1 class="mt-2 font-display text-3xl text-white">Tu vitrina personal</h1>
                            <p class="mt-2 text-sm text-white/70">Revisa cantidades y solicita tu cotizacion.</p>
                        </div>
                        <Link href="/catalogo" class="button-secondary text-xs">Seguir comprando</Link>
                    </div>

                    <FlashMessages />

                    <div v-if="items.length" class="mt-8 card-dark p-6">
                        <form method="POST" action="/carrito/actualizar">
                            <input type="hidden" name="_token" :value="csrf" />
                            <table class="cart-table text-sm">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in items" :key="item.product_id">
                                        <td class="font-semibold text-white">{{ item.name }}</td>
                                        <td>Q {{ formatMoney(item.price) }}</td>
                                        <td>
                                            <input
                                                type="number"
                                                min="0"
                                                :name="`items[${item.product_id}]`"
                                                :value="item.qty"
                                                class="cart-input w-20 text-sm"
                                            />
                                        </td>
                                        <td>Q {{ formatMoney(item.price * item.qty) }}</td>
                                        <td class="text-right">
                                            <button type="submit" :form="`remove-${item.product_id}`" class="text-xs font-semibold text-rose-400 hover:text-rose-300">Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="cart-summary">
                                <div class="w-full max-w-md">
                                    <label class="text-xs uppercase text-white/50">Notas para el pedido</label>
                                    <textarea name="notes" rows="2" class="cart-input mt-2 w-full text-sm">{{ notes }}</textarea>
                                </div>
                                <button type="submit" class="button-secondary">Actualizar carrito</button>
                                <div class="text-right">
                                    <p class="text-xs uppercase text-white/50">Subtotal estimado</p>
                                    <p class="text-xl font-semibold text-white">Q {{ formatMoney(subtotal) }}</p>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div v-else class="cart-empty mt-8 p-10 text-center text-white/70">
                        Tu carrito esta vacio.
                    </div>

                    <div v-if="items.length" class="mt-8 flex justify-end">
                        <Link href="/cotizacion" class="button-primary">Enviar cotizacion</Link>
                    </div>

                    <form v-for="item in items" :id="`remove-${item.product_id}`" :key="`remove-${item.product_id}`" method="POST" :action="`/carrito/${item.product_id}`" class="hidden">
                        <input type="hidden" name="_token" :value="csrf" />
                        <input type="hidden" name="_method" value="DELETE" />
                    </form>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';
import FlashMessages from '../Shared/FlashMessages.vue';

defineProps({
    items: Array,
    subtotal: Number,
    notes: String,
});

const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const formatMoney = (value) => Number(value ?? 0).toLocaleString('es-GT', { minimumFractionDigits: 2 });
</script>
