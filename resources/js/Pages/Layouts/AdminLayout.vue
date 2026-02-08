<template>
    <div class="admin-shell flex min-h-screen">
        <aside class="admin-sidebar w-64 text-white">
            <div class="px-6 py-6">
                <img :src="logoSimple" alt="GT Hobby" class="h-10 w-auto" />
                <p class="mt-2 text-xs text-white/70">Panel Admin</p>
            </div>
            <nav class="space-y-1 px-4 text-sm">
                <Link class="admin-nav-link" href="/admin" :class="{ 'is-active': isActive('/admin', true) }">
                    <IconHome />
                    Dashboard
                </Link>
                <Link class="admin-nav-link" href="/admin/products" :class="{ 'is-active': isActive('/admin/products') }">
                    <IconList />
                    Productos
                </Link>
                <Link class="admin-nav-link" href="/admin/categories" :class="{ 'is-active': isActive('/admin/categories') }">
                    <IconGrid />
                    Categorias
                </Link>
                <Link class="admin-nav-link" href="/admin/promotions" :class="{ 'is-active': isActive('/admin/promotions') }">
                    <IconCard />
                    Promociones
                </Link>
                <Link class="admin-nav-link" href="/admin/featured" :class="{ 'is-active': isActive('/admin/featured') }">
                    <IconTag />
                    Destacados
                </Link>
                <Link class="admin-nav-link" href="/admin/quotes" :class="{ 'is-active': isActive('/admin/quotes') }">
                    <IconDoc />
                    Cotizaciones
                </Link>
            </nav>
        </aside>
        <div class="flex-1">
            <header class="admin-header flex items-center justify-between px-8 py-4">
                <div>
                    <h1 class="font-display text-xl text-ls-navy">{{ title }}</h1>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <span class="text-slate-600">{{ user?.name }}</span>
                    <form method="POST" action="/logout">
                        <input type="hidden" name="_token" :value="csrf" />
                        <button class="rounded-full bg-ls-blue px-4 py-2 text-xs font-semibold text-white">Salir</button>
                    </form>
                </div>
            </header>
            <main class="px-8 py-8">
                <FlashMessages />
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import FlashMessages from '../Shared/FlashMessages.vue';
import IconHome from '../Shared/icons/IconHome.vue';
import IconList from '../Shared/icons/IconList.vue';
import IconGrid from '../Shared/icons/IconGrid.vue';
import IconCard from '../Shared/icons/IconCard.vue';
import IconDoc from '../Shared/icons/IconDoc.vue';
import IconTag from '../Shared/icons/IconTag.vue';

const props = defineProps({
    title: { type: String, default: 'Dashboard' },
});

const page = usePage();
const user = computed(() => page.props.auth?.user);
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

const isActive = (path, exact = false) => {
    if (exact) {
        return page.url === path;
    }
    return page.url.startsWith(path);
};
const logoSimple = '/brand/logo_simple.png';
</script>
