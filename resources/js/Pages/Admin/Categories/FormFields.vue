<template>
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label class="block text-sm font-semibold text-slate-700">Nombre</label>
            <input v-model="form.name" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
            <p v-if="form.errors.name" class="mt-1 text-xs text-rose-600">{{ form.errors.name }}</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Orden</label>
            <input v-model="form.sort_order" type="number" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
        </div>
        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input v-model="form.is_active" type="checkbox" class="rounded border-slate-300" />
            Activo
        </label>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700">Icono (opcional)</label>
            <div class="relative mt-2">
                <button
                    type="button"
                    class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white/90 px-4 py-3 text-sm"
                    @click="isOpen = !isOpen"
                >
                    <span class="flex items-center gap-3">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-ls-blue">
                            <svg v-if="selectedIcon" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path :d="selectedIcon.path"></path>
                            </svg>
                        </span>
                        <span class="text-slate-700">{{ selectedIcon?.label || 'Sin icono' }}</span>
                    </span>
                    <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M6 9l6 6 6-6"></path>
                    </svg>
                </button>
                <div
                    v-if="isOpen"
                    class="absolute z-10 mt-2 w-full rounded-2xl border border-slate-200 bg-white p-3 shadow-lg"
                >
                    <button
                        type="button"
                        class="mb-2 flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm text-slate-600 hover:bg-slate-50"
                        @click="selectIcon('')"
                    >
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-400">-</span>
                        Sin icono
                    </button>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4">
                        <button
                            v-for="icon in iconOptions"
                            :key="icon.key"
                            type="button"
                            class="flex items-center gap-3 rounded-xl px-3 py-2 text-xs font-semibold uppercase transition"
                            :class="form.icon === icon.key ? 'bg-ls-blue/10 text-ls-blue' : 'text-slate-600 hover:bg-slate-50'"
                            @click="selectIcon(icon.key)"
                        >
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path :d="icon.path"></path>
                                </svg>
                            </span>
                            <span class="text-[10px]">{{ icon.label }}</span>
                        </button>
                    </div>
                </div>
            </div>
            <p class="mt-2 text-xs text-slate-500">Selecciona un icono visual para la categoria.</p>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    form: Object,
});

const iconOptions = [
    { key: 'light', label: 'Luz', path: 'M12 3a6 6 0 0 0-3 11v3h6v-3a6 6 0 0 0-3-11z' },
    { key: 'shield', label: 'Seguridad', path: 'M12 3l7 4v5c0 5-3.5 9-7 9s-7-4-7-9V7l7-4z' },
    { key: 'wifi', label: 'WiFi', path: 'M4 9a12 12 0 0 1 16 0M7 12a7.5 7.5 0 0 1 10 0M10 15a3 3 0 0 1 4 0' },
    { key: 'solar', label: 'Solar', path: 'M12 4v3M12 17v3M4 12h3M17 12h3M6 6l2 2M16 16l2 2M6 18l2-2M16 8l2-2M9 12h6v6H9z' },
    { key: 'voice', label: 'Voz', path: 'M12 5a3 3 0 0 1 3 3v4a3 3 0 0 1-6 0V8a3 3 0 0 1 3-3zM7 12a5 5 0 0 0 10 0M12 17v3' },
    { key: 'automation', label: 'Auto', path: 'M5 12h4l3-5 3 10 3-5h1' },
    { key: 'energy', label: 'Energia', path: 'M13 2L4 14h7l-1 8 10-12h-7z' },
    { key: 'access', label: 'Accesos', path: 'M12 7a5 5 0 1 1 0 10H5v-4h7a1 1 0 1 0 0-2H6V7h6z' },
    { key: 'camera', label: 'Camara', path: 'M4 7h4l2-2h4l2 2h4v10H4z' },
    { key: 'sensor', label: 'Sensor', path: 'M12 3a9 9 0 0 1 9 9h-3a6 6 0 0 0-6-6V3zM3 12h3a6 6 0 0 0 6 6v3a9 9 0 0 1-9-9z' },
    { key: 'router', label: 'Router', path: 'M4 8h16v6H4zM7 14v2M12 14v2M17 14v2' },
    { key: 'panel', label: 'Panel', path: 'M4 6h16v12H4zM8 6v12M16 6v12M4 10h16M4 14h16' },
    { key: 'voice-assist', label: 'Asistente', path: 'M12 5a4 4 0 0 1 4 4v2a4 4 0 0 1-8 0V9a4 4 0 0 1 4-4zM8 15a4 4 0 0 0 8 0' },
    { key: 'lock', label: 'Cerradura', path: 'M6 10h12v10H6zM9 10V7a3 3 0 0 1 6 0v3' },
    { key: 'power', label: 'Energia AC', path: 'M12 3v6M8.5 5.5a5 5 0 1 0 7 0' },
    { key: 'cloud', label: 'Nube', path: 'M7 18h9a4 4 0 0 0 0-8 5 5 0 0 0-9-2A4 4 0 0 0 7 18z' },
    { key: 'bulb', label: 'Foco', path: 'M9 21h6M10 17h4M12 3a6 6 0 0 0-3 11v3h6v-3a6 6 0 0 0-3-11z' },
    { key: 'door', label: 'Puerta', path: 'M5 3h10v18H5zM13 12h1' },
    { key: 'bell', label: 'Timbre', path: 'M12 4a6 6 0 0 1 6 6v4l2 2H4l2-2v-4a6 6 0 0 1 6-6z' },
    { key: 'phone', label: 'Telefono', path: 'M6 3h12v18H6zM10 18h4' },
    { key: 'camera-out', label: 'Exterior', path: 'M5 8h8l2-2h4v12H5z' },
    { key: 'chip', label: 'Chip', path: 'M9 3h6v3h3v6h-3v3H9v-3H6V6h3V3z' },
    { key: 'thermo', label: 'Clima', path: 'M10 4a2 2 0 0 1 4 0v8a4 4 0 1 1-4 0z' },
    { key: 'plug', label: 'Enchufe', path: 'M8 3v6M16 3v6M6 9h12v4a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V9z' },
    { key: 'graph', label: 'Analitica', path: 'M4 20V10M10 20V6M16 20v-4M22 20H2' },
    { key: 'link', label: 'Conectado', path: 'M10 13a5 5 0 0 1 0-7l2-2a5 5 0 0 1 7 7l-1 1' },
    { key: 'grid', label: 'Modulos', path: 'M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z' },
    { key: 'sun', label: 'Sol', path: 'M12 4v2M12 18v2M4 12h2M18 12h2M6 6l1.5 1.5M16.5 16.5 18 18M6 18l1.5-1.5M16.5 7.5 18 6' },
    { key: 'wave', label: 'Energia', path: 'M3 12h4l2-4 4 8 2-4h4' },
];

const selectedIcon = computed(() => iconOptions.find((icon) => icon.key === props.form.icon));

const isOpen = ref(false);

const selectIcon = (key) => {
    props.form.icon = key;
    isOpen.value = false;
};
</script>
