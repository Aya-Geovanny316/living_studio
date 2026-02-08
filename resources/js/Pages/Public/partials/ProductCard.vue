<template>
    <Link
        :href="`/producto/${product.slug}`"
        class="card-dark block overflow-hidden transition group"
        @mouseenter="startHover"
        @mouseleave="stopHover"
    >
        <div class="aspect-[4/3] bg-[var(--bg-750)]">
            <img
                v-if="currentImage"
                :src="currentImage.url"
                :alt="product.name"
                class="h-full w-full object-cover"
                :style="{ objectPosition: currentImage.position || 'center' }"
            />
            <div v-else class="flex h-full items-center justify-center bg-[var(--bg-700)] text-white/70">Imagen no disponible</div>
        </div>
        <div class="p-5">
            <div class="flex items-center justify-between text-xs uppercase tracking-wide text-white/70">
                <span>{{ product.category?.name }}</span>
                <span v-if="imageList.length > 1" class="chip">{{ imageList.length }} vistas</span>
                <span v-else class="chip">Nuevo</span>
            </div>
            <h3 class="mt-2 text-lg font-semibold text-[var(--text-strong)] group-hover:text-white">{{ product.name }}</h3>
            <p class="mt-2 text-sm text-white/80" v-if="product.short_description">Marca: {{ product.short_description }}</p>
            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm">
                    <span v-if="discountPercent" class="mr-2 text-[var(--accent-red)] line-through">Q {{ formatMoney(product.price_estimate) }}</span>
                    <span class="font-semibold text-[var(--accent-red)]">Q {{ formatMoney(discountedPrice) }}</span>
                </div>
                <span class="text-sm font-semibold text-white/80 group-hover:text-[var(--accent-red)]">Ver detalle</span>
            </div>
        </div>
    </Link>
</template>

<script setup>
import { computed, onBeforeUnmount, ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    product: Object,
});

const imageList = computed(() => {
    const rawImages = props.product.images || [];
    return rawImages.map((raw) => {
        if (typeof raw === 'string') {
            return { url: raw, position: props.product.image_position || 'center' };
        }
        return { url: raw.url, position: raw.position || 'center' };
    });
});

const index = ref(0);
const timer = ref(null);

const currentImage = computed(() => imageList.value[index.value] || null);

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

const startHover = () => {
    if (imageList.value.length <= 1) {
        return;
    }
    stopHover();
    timer.value = setInterval(() => {
        index.value = (index.value + 1) % imageList.value.length;
    }, 1200);
};

const stopHover = () => {
    if (timer.value) {
        clearInterval(timer.value);
        timer.value = null;
    }
    index.value = 0;
};

onBeforeUnmount(() => {
    stopHover();
});

const formatMoney = (value) => Number(value ?? 0).toLocaleString('es-GT', { minimumFractionDigits: 2 });
</script>
