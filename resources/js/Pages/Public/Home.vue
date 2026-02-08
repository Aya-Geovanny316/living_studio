<template>
    <PublicLayout>
        <div class="page-dark">
            <section class="hero">
                <div class="mx-auto max-w-6xl px-6 hero-inner">
                    <div>
                        <span class="chip">Coleccionables premium</span>
                        <h1 class="hero-title">El mundo del hobby en una sola vitrina</h1>
                        <p class="hero-copy text-white">
                            Figuras de accion, modelos a escala, herramientas y kits para constructores exigentes.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <Link href="/catalogo" class="button-primary">Explorar catalogo</Link>
                            <Link href="/cotizacion" class="button-secondary">Pedir cotizacion</Link>
                        </div>
                    </div>
                    <div class="banner">
                        <img :src="bannerImage" alt="GT Hobby banner" />
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="preorder-card">
                        <p class="section-kicker">Novedades</p>
                        <h2 class="section-title">Lanzamientos de temporada</h2>
                        <p class="text-white/85">Preventas exclusivas y piezas limitadas para coleccionistas.</p>
                        <div class="mt-6">
                            <div v-if="novedadesList.length === 0" class="text-sm text-white/70">
                                Agrega novedades desde el administrador.
                            </div>
                            <div v-else class="novedades-scroll" ref="novedadesScroll">
                                <div
                                    v-for="item in novedadesList"
                                    :key="item.id"
                                    class="preorder-item novedad-slide transition hover:translate-x-1"
                                    :class="novedadLink(item) ? 'cursor-pointer is-clickable' : 'cursor-default'"
                                    :data-href="novedadLink(item) || ''"
                                >
                                    <div v-if="novedadImage(item)" class="preorder-thumb overflow-hidden rounded-[20px] bg-[var(--bg-700)]">
                                        <img :src="novedadImage(item)" :alt="item.title" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="text-base font-semibold text-white">{{ item.title }}</p>
                                        <p class="text-sm text-white/70">{{ item.subtitle }}</p>
                                        <p v-if="item.product && item.discount_percent" class="mt-2 text-sm text-white/80">
                                            <span class="line-through text-white/50">Q {{ formatMoney(item.product.price_estimate) }}</span>
                                            <span class="ml-2 text-[var(--accent-red)]">Q {{ formatMoney(discounted(item.product.price_estimate, item.discount_percent)) }}</span>
                                        </p>
                                    </div>
                                    <span class="chip">Nuevo</span>
                                </div>
                            </div>
                            <div v-if="novedadesList.length > 1" class="promo-dots">
                                <button
                                    v-for="(item, idx) in novedadesList"
                                    :key="item.id"
                                    type="button"
                                    class="promo-dot"
                                    :class="idx === novedadIndex ? 'is-active' : ''"
                                    @click="setNovedad(idx)"
                                    aria-label="Cambiar novedad"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="section-header">
                        <p class="section-kicker">Explora la vitrina</p>
                        <h2 class="section-title">Categorias</h2>
                    </div>
                    <div class="grid-4">
                        <Link v-for="category in categories" :key="category.id" :href="`/catalogo?category=${category.slug}`" class="card-dark p-6">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-[18px] bg-[rgba(255,60,60,0.15)] text-[var(--accent-red)]">
                                <span class="text-sm font-semibold">{{ category.name.slice(0, 2).toUpperCase() }}</span>
                            </div>
                            <p class="font-semibold text-[var(--text-strong)]">{{ category.name }}</p>
                            <p class="mt-2 text-sm text-white/80">Encuentra lo que buscas en minutos.</p>
                        </Link>
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="section-header">
                        <p class="section-kicker">Coleccion premium</p>
                        <h2 class="section-title">Destacados</h2>
                    </div>
                    <div class="grid-3">
                        <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="mx-auto max-w-6xl px-6">
                    <div class="section-header">
                        <p class="section-kicker">Novedades y bundles</p>
                        <h2 class="section-title">Promociones</h2>
                    </div>
                    <div class="promo-scroll" ref="promoScroll">
                        <div
                            v-for="promo in promotions"
                            :key="promo.id"
                            class="promo-card promo-slide"
                            :class="promoHref(promo) ? 'promo-card-link is-clickable' : ''"
                            :data-href="promoHref(promo) || ''"
                        >
                            <img v-if="promoImage(promo)" :src="promoImage(promo)" :alt="promo.title" />
                            <div class="promo-card-content">
                                <p class="section-kicker">GT Hobby</p>
                                <h3 class="text-2xl font-semibold text-[var(--text-strong)]">{{ promo.title }}</h3>
                                <p class="mt-2 text-sm text-white/80">{{ promo.subtitle }}</p>
                                <p v-if="promo.product && promo.discount_percent" class="mt-3 text-sm text-white/80">
                                    <span class="line-through text-white/50">Q {{ formatMoney(promo.product.price_estimate) }}</span>
                                    <span class="ml-2 text-[var(--accent-red)]">Q {{ formatMoney(discounted(promo.product.price_estimate, promo.discount_percent)) }}</span>
                                </p>
                                <span v-if="promoHref(promo)" class="button-secondary mt-4">Ver mas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';
import ProductCard from './partials/ProductCard.vue';

const props = defineProps({
    categories: Array,
    featuredProducts: Array,
    promotions: Array,
    novedades: Array,
});

const novedadesList = computed(() => props.novedades || []);
const novedadesScroll = ref(null);
let novedadesTimer = null;
const novedadIndex = ref(0);
let novedadesDragging = false;
let novedadesMoved = false;
let novedadesStartX = 0;
let novedadesStartLeft = 0;

const promoScroll = ref(null);
let promoTimer = null;
let isDragging = false;
let promoMoved = false;
let dragStartX = 0;
let dragScrollLeft = 0;

const novedadImage = (item) => {
    const image = item?.product?.images?.[0];
    if (image) {
        return image.url || image;
    }
    return item?.image_path || '';
};

const novedadLink = (item) => {
    if (item?.product?.slug) {
        return `/producto/${item.product.slug}`;
    }
    return item?.link || '';
};

const promoImage = (promo) => {
    const image = promo?.product?.images?.[0];
    if (image) {
        return image.url || image;
    }
    return promo?.image_path || '';
};

const promoHref = (promo) => {
    if (promo?.product?.slug) {
        return `/producto/${promo.product.slug}`;
    }
    return promo?.link || '';
};

const updateNovedadIndex = () => {
    if (!novedadesScroll.value) {
        return;
    }
    const slides = novedadesScroll.value.querySelectorAll('.novedad-slide');
    if (!slides.length) {
        return;
    }
    const center = novedadesScroll.value.scrollLeft + novedadesScroll.value.clientWidth / 2;
    let closestIndex = 0;
    let closestDistance = Number.POSITIVE_INFINITY;
    slides.forEach((slide, idx) => {
        const slideCenter = slide.offsetLeft + slide.clientWidth / 2;
        const distance = Math.abs(center - slideCenter);
        if (distance < closestDistance) {
            closestDistance = distance;
            closestIndex = idx;
        }
    });
    novedadIndex.value = closestIndex;
};

const setNovedad = (index) => {
    if (!novedadesScroll.value) {
        return;
    }
    const slides = novedadesScroll.value.querySelectorAll('.novedad-slide');
    const target = slides[index];
    if (target) {
        const offset = target.offsetLeft - (novedadesScroll.value.clientWidth - target.clientWidth) / 2;
        novedadesScroll.value.scrollTo({ left: offset, behavior: 'smooth' });
        novedadIndex.value = index;
    }
};

const onNovedadesPointerDown = (event) => {
    if (!novedadesScroll.value) {
        return;
    }
    novedadesDragging = true;
    novedadesMoved = false;
    novedadesScroll.value.classList.add('is-dragging');
    novedadesScroll.value.setPointerCapture?.(event.pointerId);
    novedadesStartX = event.clientX;
    novedadesStartLeft = novedadesScroll.value.scrollLeft;
};

const onNovedadesPointerMove = (event) => {
    if (!novedadesDragging || !novedadesScroll.value) {
        return;
    }
    const delta = event.clientX - novedadesStartX;
    if (Math.abs(delta) > 6) {
        novedadesMoved = true;
        event.preventDefault();
    }
    novedadesScroll.value.scrollLeft = novedadesStartLeft - delta;
};

const onNovedadesPointerUp = (event) => {
    if (!novedadesScroll.value) {
        return;
    }
    novedadesDragging = false;
    novedadesScroll.value.classList.remove('is-dragging');
    novedadesScroll.value.releasePointerCapture?.(event.pointerId);
    if (!novedadesMoved) {
        const hit = document.elementFromPoint(event.clientX, event.clientY);
        const target = hit?.closest?.('.novedad-slide');
        const href = target?.dataset?.href;
        if (href) {
            router.visit(href);
        }
    }
};

const startNovedades = () => {
    if (!novedadesScroll.value || novedadesList.value.length <= 1) {
        return;
    }
    novedadesScroll.value.addEventListener('pointerdown', onNovedadesPointerDown);
    novedadesScroll.value.addEventListener('pointermove', onNovedadesPointerMove);
    novedadesScroll.value.addEventListener('pointerup', onNovedadesPointerUp);
    novedadesScroll.value.addEventListener('pointerleave', onNovedadesPointerUp);
    novedadesScroll.value.addEventListener('scroll', updateNovedadIndex, { passive: true });
    novedadesTimer = setInterval(() => {
        if (!novedadesScroll.value || novedadesList.value.length <= 1) {
            return;
        }
        const nextIndex = (novedadIndex.value + 1) % novedadesList.value.length;
        setNovedad(nextIndex);
    }, 7000);
    updateNovedadIndex();
};

const stopNovedades = () => {
    if (novedadesScroll.value) {
        novedadesScroll.value.removeEventListener('pointerdown', onNovedadesPointerDown);
        novedadesScroll.value.removeEventListener('pointermove', onNovedadesPointerMove);
        novedadesScroll.value.removeEventListener('pointerup', onNovedadesPointerUp);
        novedadesScroll.value.removeEventListener('pointerleave', onNovedadesPointerUp);
        novedadesScroll.value.removeEventListener('scroll', updateNovedadIndex);
    }
    if (novedadesTimer) {
        clearInterval(novedadesTimer);
        novedadesTimer = null;
    }
};

const onPromoWheel = (event) => {
    if (!promoScroll.value) {
        return;
    }
    if (Math.abs(event.deltaY) > Math.abs(event.deltaX)) {
        event.preventDefault();
        promoScroll.value.scrollBy({ left: event.deltaY, behavior: 'smooth' });
    }
};

const onPromoPointerDown = (event) => {
    if (!promoScroll.value) {
        return;
    }
    isDragging = true;
    promoMoved = false;
    promoScroll.value.classList.add('is-dragging');
    promoScroll.value.setPointerCapture?.(event.pointerId);
    dragStartX = event.clientX;
    dragScrollLeft = promoScroll.value.scrollLeft;
};

const onPromoPointerMove = (event) => {
    if (!isDragging || !promoScroll.value) {
        return;
    }
    const delta = event.clientX - dragStartX;
    if (Math.abs(delta) > 6) {
        promoMoved = true;
        event.preventDefault();
    }
    promoScroll.value.scrollLeft = dragScrollLeft - delta;
};

const onPromoPointerUp = (event) => {
    if (!promoScroll.value) {
        return;
    }
    isDragging = false;
    promoScroll.value.classList.remove('is-dragging');
    promoScroll.value.releasePointerCapture?.(event.pointerId);
    if (!promoMoved) {
        const hit = document.elementFromPoint(event.clientX, event.clientY);
        const target = hit?.closest?.('.promo-slide');
        const href = target?.dataset?.href;
        if (href) {
            router.visit(href);
        }
    }
};

const startPromoScroll = () => {
    if (!promoScroll.value || !props.promotions?.length || props.promotions.length <= 1) {
        return;
    }
    promoScroll.value.addEventListener('wheel', onPromoWheel, { passive: false });
    promoScroll.value.addEventListener('pointerdown', onPromoPointerDown);
    promoScroll.value.addEventListener('pointermove', onPromoPointerMove);
    promoScroll.value.addEventListener('pointerup', onPromoPointerUp);
    promoScroll.value.addEventListener('pointerleave', onPromoPointerUp);
    promoTimer = setInterval(() => {
        const el = promoScroll.value;
        if (!el) {
            return;
        }
        const step = Math.max(240, Math.floor(el.clientWidth * 0.8));
        const next = el.scrollLeft + step;
        if (next + el.clientWidth >= el.scrollWidth - 4) {
            el.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            el.scrollBy({ left: step, behavior: 'smooth' });
        }
    }, 6500);
};

const stopPromoScroll = () => {
    if (promoScroll.value) {
        promoScroll.value.removeEventListener('wheel', onPromoWheel);
        promoScroll.value.removeEventListener('pointerdown', onPromoPointerDown);
        promoScroll.value.removeEventListener('pointermove', onPromoPointerMove);
        promoScroll.value.removeEventListener('pointerup', onPromoPointerUp);
        promoScroll.value.removeEventListener('pointerleave', onPromoPointerUp);
    }
    if (promoTimer) {
        clearInterval(promoTimer);
        promoTimer = null;
    }
};

onMounted(() => {
    startNovedades();
    startPromoScroll();
});

onBeforeUnmount(() => {
    stopNovedades();
    stopPromoScroll();
});

const discounted = (price, percent) => {
    const value = Number(price ?? 0);
    const pct = Number(percent ?? 0);
    return Math.max(0, value - value * (pct / 100));
};

const formatMoney = (value) => Number(value ?? 0).toLocaleString('es-GT', { minimumFractionDigits: 2 });

const bannerImage = '/brand/banner.png';
</script>




