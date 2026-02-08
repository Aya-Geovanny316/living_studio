<template>
    <div v-if="links && links.length > 3" class="mt-6 flex flex-wrap gap-2">
        <Link
            v-for="(link, index) in links"
            :key="index"
            :href="link.url || ''"
            class="rounded-full px-4 py-2 text-xs font-semibold"
            :class="[
                link.active ? 'bg-[var(--accent-red)] text-white shadow-[0_0_16px_rgba(255,60,60,0.35)]' : 'bg-[var(--bg-800)] text-white/80 ring-1 ring-white/10',
                !link.url ? 'opacity-50 cursor-not-allowed' : 'hover:text-[var(--accent-red)]',
            ]"
            v-html="link.label"
        />
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>
