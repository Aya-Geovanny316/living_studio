<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="relative flex h-[70vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-xl">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-6 py-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Editor</p>
                    <h3 class="text-lg font-semibold text-slate-800">{{ title }}</h3>
                </div>
                <div class="min-w-[180px]">
                    <label class="text-xs uppercase text-slate-500">Proporcion</label>
                    <select v-model="localAspect" class="mt-2 w-full rounded-xl border-slate-200 bg-white/90 text-sm">
                        <option :value="null">Libre</option>
                        <option :value="1">1:1</option>
                        <option :value="4 / 3">4:3</option>
                        <option :value="16 / 9">16:9</option>
                        <option :value="3 / 2">3:2</option>
                    </select>
                </div>
                <button
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500"
                    title="Cerrar"
                    aria-label="Cerrar"
                    @click="cancel"
                >
                    x
                </button>
            </div>
            <div class="flex-1 overflow-hidden p-4">
                <div class="ls-cropper-frame flex items-center justify-center overflow-hidden rounded-2xl bg-slate-50 p-4">
                    <img ref="imageRef" :src="src" alt="Crop" class="max-h-full max-w-full" />
                </div>
            </div>
            <div class="sticky bottom-0 mt-auto flex items-center justify-end gap-3 border-t border-slate-100 bg-white px-6 py-4">
                <button type="button" class="rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600" @click="cancel">
                    Cancelar
                </button>
                <button type="button" class="rounded-full bg-ls-indigo px-6 py-2 text-xs font-semibold text-white shadow" @click="confirm">
                    Recortar
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import Cropper from 'cropperjs';

const props = defineProps({
    show: Boolean,
    src: String,
    title: {
        type: String,
        default: 'Recortar imagen',
    },
    aspectRatio: {
        type: Number,
        default: null,
    },
    mime: {
        type: String,
        default: 'image/jpeg',
    },
    maxWidth: {
        type: Number,
        default: 1600,
    },
    maxHeight: {
        type: Number,
        default: 1600,
    },
});

const emit = defineEmits(['confirm', 'cancel', 'update:aspectRatio']);

const imageRef = ref(null);
const cropper = ref(null);

const localAspect = computed({
    get: () => props.aspectRatio,
    set: (value) => emit('update:aspectRatio', value),
});

const destroyCropper = () => {
    if (cropper.value) {
        cropper.value.destroy();
        cropper.value = null;
    }
};

const initCropper = async () => {
    await nextTick();
    if (!imageRef.value) {
        return;
    }
    destroyCropper();
    cropper.value = new Cropper(imageRef.value, {
        viewMode: 1,
        autoCropArea: 1,
        background: false,
        responsive: true,
        minContainerWidth: 0,
        minContainerHeight: 0,
        aspectRatio: props.aspectRatio ?? NaN,
    });
};

const confirm = () => {
    if (!cropper.value) {
        emit('confirm', null);
        return;
    }
    const canvas = cropper.value.getCroppedCanvas({
        imageSmoothingQuality: 'high',
        maxWidth: props.maxWidth,
        maxHeight: props.maxHeight,
    });
    if (!canvas) {
        emit('confirm', null);
        return;
    }
    canvas.toBlob((blob) => emit('confirm', blob), props.mime, 0.9);
};

const cancel = () => {
    emit('cancel');
};

watch(
    () => [props.show, props.src],
    ([show, src]) => {
        if (show && src) {
            initCropper();
        } else {
            destroyCropper();
        }
    }
);

watch(
    () => props.aspectRatio,
    (value) => {
        if (cropper.value) {
            cropper.value.setAspectRatio(value ?? NaN);
        }
    }
);

onBeforeUnmount(() => {
    destroyCropper();
});
</script>
