<template>
    <div class="min-h-screen ls-customer-bg">
        <header class="bg-white/80 backdrop-blur border-b border-slate-200">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <Link href="/" class="flex items-center gap-3">
                    <img :src="logoSimple" alt="GT Hobby" class="h-14 w-auto" />
                    <div>
                        <p class="font-display text-lg text-ls-navy">GT Hobby</p>
                        <p class="text-xs text-slate-500">Coleccionables y modelismo</p>
                    </div>
                </Link>
                <div class="flex items-center gap-3 text-sm">
                    <span class="text-slate-600">{{ user?.name }}</span>
                    <form method="POST" action="/logout">
                        <input type="hidden" name="_token" :value="csrf" />
                        <button class="rounded-full bg-ls-blue px-4 py-2 text-xs font-semibold text-white">Salir</button>
                    </form>
                </div>
            </div>
        </header>
        <main class="mx-auto max-w-6xl px-6 py-12">
            <FlashMessages />
            <slot />
        </main>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import FlashMessages from '../Shared/FlashMessages.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const logoSimple = '/brand/logo_simple.png';
</script>
