<template>
    <PublicLayout>
        <div class="page-dark">
            <section class="section">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="mb-6 text-xs uppercase tracking-[0.3em] text-white/70">
                        <Link href="/catalogo" class="hover:text-[var(--accent-red)]">Catalogo</Link>
                        <span class="mx-2">/</span>
                        <span>{{ product.category?.name }}</span>
                    </div>
                    <div class="grid gap-10 lg:grid-cols-2">
                        <div class="space-y-4">
                            <div class="card-dark overflow-hidden">
                                <button v-if="heroImage" type="button" class="block w-full" @click="openLightbox(localIndex)">
                                    <img :src="heroImage.url" :alt="product.name" class="h-96 w-full object-cover" :style="{ objectPosition: heroImage.position || 'center' }" />
                                </button>
                                <div v-else class="flex h-96 items-center justify-center bg-[var(--bg-700)] text-white/70">Imagen no disponible</div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <button
                                    v-for="(image, index) in images"
                                    :key="index"
                                    type="button"
                                    class="card-dark overflow-hidden transition ring-1 ring-transparent hover:ring-[var(--accent-red)]"
                                    :class="index === selectedIndex ? 'ring-[var(--accent-red)]' : ''"
                                    @click="selectImage(index)"
                                >
                                    <img :src="image.url" :alt="product.name" class="h-24 w-full object-cover" :style="{ objectPosition: image.position || 'center' }" />
                                </button>
                            </div>
                        </div>
                        <div>
                            <span class="chip">{{ product.category?.name }}</span>
                            <h1 class="mt-4 text-4xl font-semibold text-[var(--text-strong)]">{{ product.name }}</h1>
                            <p class="mt-4 text-base text-white/80">{{ product.description }}</p>
                            <p v-if="product.short_description" class="mt-3 text-sm text-white/70">Marca: <span class="text-white">{{ product.short_description }}</span></p>

                            <div class="card-dark mt-6 flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-white/70">Precio estimado</p>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <span v-if="discountPercent" class="price-original text-base">Q {{ formatMoney(product.price_estimate) }}</span>
                                        <span class="text-2xl font-semibold text-[var(--accent-red)]">Q {{ formatMoney(discountedPrice) }}</span>
                                    </div>
                                </div>
                                <form method="POST" action="/carrito/agregar">
                                    <input type="hidden" name="_token" :value="csrf" />
                                    <input type="hidden" name="product_id" :value="product.id" />
                                    <button type="submit" class="button-primary">Agregar al carrito</button>
                                </form>
                            </div>

                            <div v-if="specs.length" class="mt-8">
                                <h3 class="text-xl font-semibold text-[var(--text-strong)]">Detalles tecnicos</h3>
                                <dl class="mt-4 grid gap-3 card-dark p-6 text-sm text-white/80">
                                    <div v-for="spec in specs" :key="spec.label" class="flex justify-between border-b border-white/10 pb-2">
                                        <dt class="font-semibold text-[var(--text-strong)]">{{ spec.label }}</dt>
                                        <dd>{{ spec.value }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section v-if="related.length" class="section">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="section-header">
                        <p class="section-kicker">Completa tu coleccion</p>
                        <h2 class="section-title">Productos relacionados</h2>
                    </div>
                    <div class="grid-4">
                        <ProductCard v-for="item in related" :key="item.id" :product="item" />
                    </div>
                </div>
            </section>
        </div>

        <div v-if="lightboxOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 px-4 py-10" @click.self="closeLightbox">
            <button type="button" class="fixed right-6 top-6 z-[120] flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-black/85 text-white/90 hover:text-white" @click="closeLightbox" aria-label="Cerrar">
                <svg viewBox="0 0 24 24" class="h-4 w-4" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                </svg>
            </button>
            <div class="w-full max-w-5xl">
                <div class="card-dark overflow-hidden">
                    <div class="flex items-center justify-center bg-black px-4 py-6">
                        <img
                            v-if="lightboxImage"
                            :src="lightboxImage.url"
                            :alt="product.name"
                            class="max-h-[60vh] max-w-[85vw] object-contain"
                        />
                    </div>
                </div>
                <div v-if="images.length > 1" class="mt-4 flex items-center justify-center gap-3">
                    <button type="button" class="rounded-full border border-white/20 px-3 py-1 text-xs text-white/70 hover:text-white" @click="prevLightbox">
                        Anterior
                    </button>
                    <button type="button" class="rounded-full border border-white/20 px-3 py-1 text-xs text-white/70 hover:text-white" @click="nextLightbox">
                        Siguiente
                    </button>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';
import ProductCard from './partials/ProductCard.vue';

const props = defineProps({
    product: Object,
    related: Array,
});

const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

const images = computed(() => {
    const rawImages = props.product.images || [];
    return rawImages.map((raw) => {
        if (typeof raw === 'string') {
            return { url: raw, position: props.product.image_position || 'center' };
        }
        return { url: raw.url, position: raw.position || 'center' };
    });
});

const localIndex = ref(0);
const lightboxOpen = ref(false);
const lightboxIndex = ref(0);

const heroImage = computed(() => images.value[localIndex.value] || images.value[0] || null);
const lightboxImage = computed(() => images.value[lightboxIndex.value] || null);

const selectImage = (index) => {
    localIndex.value = index;
};

const openLightbox = (index = 0) => {
    lightboxIndex.value = index;
    lightboxOpen.value = true;
};

const closeLightbox = () => {
    lightboxOpen.value = false;
};

const nextLightbox = () => {
    if (!images.value.length) {
        return;
    }
    lightboxIndex.value = (lightboxIndex.value + 1) % images.value.length;
};

const prevLightbox = () => {
    if (!images.value.length) {
        return;
    }
    lightboxIndex.value = (lightboxIndex.value - 1 + images.value.length) % images.value.length;
};

const specs = computed(() => {
    const rawSpecs = props.product.specs || {};
    return Object.entries(rawSpecs).map(([label, value]) => ({ label, value }));
});

const discountPercent = computed(() => {
    const direct = Number(props.product.discount_percent || 0);
    if (direct) {
        return direct;
    }
    const promos = props.product.promotions || [];
    if (!promos.length) {
        return 0;
    }
    return Math.max(...promos.map((p) => Number(p.discount_percent || 0)));
});

const discountedPrice = computed(() => {
    const base = Number(props.product.price_estimate ?? 0);
    const pct = discountPercent.value;
    return pct ? base - base * (pct / 100) : base;
});

const formatMoney = (value) => Number(value ?? 0).toLocaleString('es-GT', { minimumFractionDigits: 2 });
</script>
