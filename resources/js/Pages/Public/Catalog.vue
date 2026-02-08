<template>
    <PublicLayout>
        <div class="page-dark">
            <section class="section">
                <div class="mx-auto max-w-6xl px-6">
                <div class="catalog-header">
                    <div>
                        <p class="section-kicker">Catalogo GT Hobby</p>
                        <h1 class="catalog-title">Figuras, modelos y kits</h1>
                        <p class="mt-3 text-sm text-white/80">Curado para coleccionistas y creadores.</p>
                    </div>
                    <Link href="/cotizacion" class="button-primary">Cotizar mi carrito</Link>
                </div>

                <FlashMessages />

                <div class="catalog-layout mt-8">
                    <aside class="catalog-sidebar">
                        <form @submit.prevent="applyFilters" class="catalog-filters">
                            <div class="catalog-filter-group">
                                <label class="catalog-label">Busqueda</label>
                                <input v-model="form.search" type="text" class="catalog-input" placeholder="Ej: figuras 1:12, Gundam, Marvel" />
                            </div>
                            <div class="catalog-filter-group">
                                <label class="catalog-label">Ordenar</label>
                                <select v-model="form.sort" class="catalog-input">
                                    <option value="">Mas nuevos</option>
                                    <option value="price_asc">Precio: menor a mayor</option>
                                    <option value="price_desc">Precio: mayor a menor</option>
                                </select>
                            </div>
                            <div class="catalog-filter-group">
                                <label class="catalog-label">Filtros</label>
                                <div class="catalog-checklist">
                                    <label class="catalog-checkline">
                                        <input v-model="form.featured" type="checkbox" class="catalog-check" />
                                        <span>Destacados</span>
                                    </label>
                                    <label class="catalog-checkline">
                                        <input v-model="form.discounted" type="checkbox" class="catalog-check" />
                                        <span>Con descuento</span>
                                    </label>
                                </div>
                            </div>
                            <div class="catalog-filter-group">
                                <label class="catalog-label">Categorias</label>
                                <div class="catalog-checklist">
                                    <label v-for="category in normalizedCategories" :key="category.id" class="catalog-checkline">
                                        <input v-model="form.category" type="checkbox" :value="category.slug" class="catalog-check" />
                                        <span>{{ category.name }}</span>
                                    </label>
                                    <p v-if="normalizedCategories.length === 0" class="text-sm text-white/60">No hay categorias activas.</p>
                                </div>
                            </div>
                            <div class="catalog-filter-group">
                                <label class="catalog-label">Marcas</label>
                                <div class="catalog-checklist">
                                    <label v-for="brand in (props.brands || [])" :key="brand" class="catalog-checkline">
                                        <input v-model="form.brand" type="checkbox" :value="brand" class="catalog-check" />
                                        <span>{{ brand }}</span>
                                    </label>
                                    <p v-if="!props.brands || props.brands.length === 0" class="text-sm text-white/60">No hay marcas registradas.</p>
                                </div>
                            </div>
                            <div class="catalog-action">
                                <button type="button" class="button-secondary" @click="clearFilters">Limpiar filtros</button>
                                <button type="submit" class="button-primary">Aplicar filtros</button>
                            </div>
                        </form>
                    </aside>
                    <div>
                        <div class="catalog-grid">
                            <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
                            <div v-if="products.data.length === 0" class="catalog-empty">No hay productos con esos filtros.</div>
                        </div>
                    </div>
                </div>

                    <Pagination :links="products.links" class="mt-10" />
                </div>
            </section>
        </div>
    </PublicLayout>
</template>

<script setup>
import { computed, reactive, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';
import ProductCard from './partials/ProductCard.vue';
import Pagination from '../Shared/Pagination.vue';
import FlashMessages from '../Shared/FlashMessages.vue';

const props = defineProps({
    categories: Array,
    brands: Array,
    products: Object,
    filters: Object,
});

const initialCategories = reactive({ value: props.categories || [] });

const categoriesSource = computed(() => {
    const list = props.categories || [];
    const hasValid = list.some((category) => category && typeof category === 'object' && (category.name || category.slug));
    return hasValid ? list : initialCategories.value;
});

watch(
    () => props.categories,
    (value) => {
        if ((value || []).some((category) => category && typeof category === 'object' && (category.name || category.slug))) {
            initialCategories.value = value || [];
        }
    }
);

const normalizedCategories = computed(() => {
    return (categoriesSource.value || [])
        .map((category) => {
            if (typeof category === 'string') {
                return { id: category, name: category, slug: category };
            }
            return {
                id: category?.id ?? category?.slug ?? category?.name ?? Math.random().toString(36).slice(2),
                name: category?.name ?? category?.slug ?? 'Categoria',
                slug: category?.slug ?? category?.name ?? '',
            };
        })
        .filter((category) => category.slug && category.name);
});

const form = reactive({
    category: [],
    search: '',
    sort: '',
    featured: false,
    discounted: false,
    brand: [],
});

const applyFilters = () => {
    router.get('/catalogo', form, { preserveState: true, preserveScroll: true, replace: true });
};

const clearFilters = () => {
    form.category = [];
    form.search = '';
    form.sort = '';
    form.featured = false;
    form.discounted = false;
    form.brand = [];
    router.get('/catalogo', {}, { preserveState: true, replace: true });
};

const syncFilters = (filters) => {
    const categories = Array.isArray(filters?.category)
        ? filters.category
        : filters?.category
          ? [filters.category]
          : [];
    form.category = categories.filter(Boolean);
    form.search = filters?.search || '';
    form.sort = filters?.sort || '';
    form.featured = filters?.featured === true || filters?.featured === '1';
    form.discounted = filters?.discounted === true || filters?.discounted === '1';
    const brands = Array.isArray(filters?.brand)
        ? filters.brand
        : filters?.brand
          ? [filters.brand]
          : [];
    form.brand = brands.filter(Boolean);
};

syncFilters(props.filters);
watch(
    () => props.filters,
    (value) => {
        syncFilters(value);
    }
);
</script>
