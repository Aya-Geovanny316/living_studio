<template>
    <div>
        <div v-if="flash.success" class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ flash.success }}
        </div>
        <div v-if="flash.error" class="mb-6 rounded-xl bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
            {{ flash.error }}
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const flash = computed(() => page.props.flash || {});
</script>
