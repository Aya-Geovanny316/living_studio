<template>
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label class="block text-sm font-semibold text-slate-700">Titulo</label>
            <input v-model="form.title" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
            <p v-if="form.errors.title" class="mt-1 text-xs text-rose-600">{{ form.errors.title }}</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Subtitulo</label>
            <input v-model="form.subtitle" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Link (opcional)</label>
            <input v-model="form.link" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Tipo</label>
            <select v-model="form.type" class="mt-2 w-full rounded-xl border-slate-200 bg-white/90 text-sm">
                <option value="promo">Promocion</option>
                <option value="novedad">Novedad</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Producto (opcional)</label>
            <select v-model="form.product_id" class="mt-2 w-full rounded-xl border-slate-200 bg-white/90 text-sm">
                <option value="">Sin producto</option>
                <option v-for="product in products" :key="product.id" :value="product.id">
                    {{ product.name }}
                </option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Descuento %</label>
            <input v-model="form.discount_percent" type="number" min="1" max="90" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Orden</label>
            <input v-model="form.sort_order" type="number" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700">Imagen</label>
            <input type="file" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 text-sm" @change="onFile" />
            <img v-if="previewImage" :src="previewImage" alt="Promo" class="mt-4 h-32 rounded-xl object-cover" />
        </div>
        <ImageCropperModal
            :show="cropperOpen"
            :src="cropSource"
            :mime="cropMime"
            :max-width="1600"
            :max-height="1600"
            v-model:aspectRatio="cropAspect"
            title="Recortar promocion"
            @confirm="handleCropConfirm"
            @cancel="handleCropCancel"
        />
        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input v-model="form.is_active" type="checkbox" class="rounded border-slate-300" />
            Activo
        </label>
    </div>
</template>

<script setup>
import { onBeforeUnmount, ref, watch } from 'vue';
import ImageCropperModal from '../../Shared/ImageCropperModal.vue';

const props = defineProps({
    form: Object,
    currentImage: String,
    products: {
        type: Array,
        default: () => [],
    },
});

const previewImage = ref(props.currentImage || '');
const cropperOpen = ref(false);
const cropSource = ref('');
const cropMime = ref('image/jpeg');
const cropName = ref('promo.jpg');
const cropAspect = ref(16 / 9);
const cropUrl = ref('');

const setCropSource = (file) => {
    if (cropUrl.value) {
        URL.revokeObjectURL(cropUrl.value);
    }
    cropUrl.value = URL.createObjectURL(file);
    cropSource.value = cropUrl.value;
    cropName.value = file.name;
    cropMime.value = file.type || 'image/jpeg';
};

const onFile = (event) => {
    const file = event.target.files?.[0];
    if (!file) {
        return;
    }
    setCropSource(file);
    cropperOpen.value = true;
};

const handleCropConfirm = (blob) => {
    if (blob) {
        const file = new File([blob], cropName.value, { type: blob.type || cropMime.value });
        props.form.image = file;
        if (previewImage.value) {
            URL.revokeObjectURL(previewImage.value);
        }
        previewImage.value = URL.createObjectURL(file);
    }
    cropperOpen.value = false;
    if (cropUrl.value) {
        URL.revokeObjectURL(cropUrl.value);
        cropUrl.value = '';
    }
};

const handleCropCancel = () => {
    cropperOpen.value = false;
    if (cropUrl.value) {
        URL.revokeObjectURL(cropUrl.value);
        cropUrl.value = '';
    }
};

watch(
    () => props.currentImage,
    (value) => {
        if (!previewImage.value) {
            previewImage.value = value || '';
        }
    }
);

onBeforeUnmount(() => {
    if (cropUrl.value) {
        URL.revokeObjectURL(cropUrl.value);
    }
});
</script>
