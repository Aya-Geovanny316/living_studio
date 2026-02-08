<template>
    <div class="page-dark">
        <header class="site-header">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6">
                <Link href="/" class="flex items-center gap-3">
                    <div class="h-16">
                        <img :src="logoPrincipal" alt="GT Hobby" class="h-full w-auto object-contain logo-glow" />
                    </div>
                </Link>
                <nav class="site-nav hidden items-center gap-6 text-sm md:flex">
                    <Link href="/catalogo" class="hover:text-[var(--accent-red)]">Catalogo</Link>
                    <Link href="/cotizacion" class="hover:text-[var(--accent-red)]">Cotizacion</Link>
                    <a href="#contacto" class="hover:text-[var(--accent-red)]">Contacto</a>
                </nav>
                <div class="flex items-center gap-4">
                    <Link href="/carrito" class="relative text-sm font-semibold text-white hover:text-[var(--accent-red)]">
                        <span class="inline-flex items-center gap-2">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4 text-white">
                                <path d="M6 6h15l-1.5 9h-12L5 3H2" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="9" cy="20" r="1.4" fill="currentColor" />
                                <circle cx="18" cy="20" r="1.4" fill="currentColor" />
                            </svg>
                            Carrito
                        </span>
                        <span class="ml-2 inline-flex h-6 w-6 items-center justify-center rounded-full bg-[var(--accent-red)] text-xs text-white shadow-[0_0_12px_rgba(255,60,60,0.4)]">
                            {{ cartCount }}
                        </span>
                    </Link>
                </div>
            </div>
        </header>
        <main>
            <slot />
        </main>
        <footer id="contacto" class="border-t border-white/10 bg-[#0f0f0f]">
            <div class="mx-auto grid max-w-6xl gap-8 px-6 py-12 md:grid-cols-3">
                <div>
                    <img :src="logoPrincipal" alt="GT Hobby" class="h-18 w-auto" />
                    <p class="mt-3 text-sm text-white/70">Catalogo moderno de modelismo, juguetes a escala y hobbies creativos.</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Contacto</p>
                    <p class="mt-2 text-sm text-white/70">hola@gthobby.com</p>
                    <p class="text-sm text-white/70">+502 4000 0000</p>
                    <p class="text-sm text-white/70">Ciudad de Guatemala, Guatemala</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Links</p>
                    <div class="mt-2 flex flex-col gap-2 text-sm text-white/70">
                        <Link href="/catalogo" class="hover:text-[var(--accent-red)]">Catalogo</Link>
                        <Link href="/cotizacion" class="hover:text-[var(--accent-red)]">Cotizacion</Link>
                        <Link href="/carrito" class="hover:text-[var(--accent-red)]">Carrito</Link>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10 py-4 text-center text-xs text-[var(--text-faint)]">
                2026 GT Hobby. Todos los derechos reservados.
            </div>
        </footer>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const cartCount = computed(() => page.props.cart?.count ?? 0);
const logoPrincipal = '/brand/logo_principal.png';
const logoSimple = '/brand/logo_simple.png';
</script>
