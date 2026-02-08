<template>
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label class="block text-sm font-semibold text-slate-700">Nombre</label>
            <input v-model="form.name" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
            <p v-if="form.errors.name" class="mt-1 text-xs text-rose-600">{{ form.errors.name }}</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Categoria</label>
            <select v-model="form.category_id" class="mt-2 w-full rounded-xl border-slate-200 bg-white/90 text-sm">
                <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                </option>
            </select>
            <p v-if="form.errors.category_id" class="mt-1 text-xs text-rose-600">{{ form.errors.category_id }}</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Precio estimado</label>
            <input v-model="form.price_estimate" type="number" step="0.01" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
            <p v-if="form.errors.price_estimate" class="mt-1 text-xs text-rose-600">{{ form.errors.price_estimate }}</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">SKU</label>
            <input v-model="form.sku" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Estado de stock</label>
            <input v-model="form.stock_status" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700">Fabricante</label>
            <input v-model="form.short_description" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" />
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700">Descripcion completa</label>
            <textarea v-model="form.description" rows="4" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm"></textarea>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700">Especificaciones</label>
            <div class="mt-3 space-y-3">
                <div v-for="(spec, index) in form.specs" :key="index" class="grid gap-3 md:grid-cols-2">
                    <input v-model="spec.key" placeholder="Etiqueta (ej: Control)" class="w-full rounded-xl border-slate-200 bg-white/80 px-4 py-2 text-sm" />
                    <div class="flex items-center gap-2">
                        <input v-model="spec.value" placeholder="Valor (ej: App + voz)" class="w-full rounded-xl border-slate-200 bg-white/80 px-4 py-2 text-sm" />
                        <button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-xs text-slate-600" @click="removeSpec(index)">Quitar</button>
                    </div>
                </div>
            </div>
            <button type="button" class="mt-3 rounded-full bg-white px-4 py-2 text-xs font-semibold text-ls-navy ring-1 ring-slate-200" @click="addSpec">
                Agregar especificacion
            </button>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700">Imagenes</label>
            <div class="mt-2 flex flex-wrap items-center gap-4">
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-ls-indigo px-4 py-2 text-xs font-semibold text-white shadow">
                    <input type="file" multiple class="hidden" @change="onFiles" />
                    Seleccionar imagenes
                </label>
                <span class="text-xs text-slate-500">Formatos: JPG, PNG. Max 4MB.</span>
            </div>
            <p v-if="imageError" class="mt-1 text-xs text-rose-600">{{ imageError }}</p>
            <div v-if="previewImages.length || form.existing_images.length" class="mt-4 flex flex-wrap gap-3">
                <div v-for="(img, idx) in form.existing_images" :key="`existing-${idx}`" class="shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-white" style="width: 80px;">
                    <div class="flex items-center justify-center bg-slate-50" style="height: 56px;">
                        <img :src="img.url" class="h-full w-full object-contain" :style="{ objectPosition: img.position || 'center' }" />
                    </div>
                    <div class="flex flex-col gap-2 border-t border-slate-100 px-2 py-2 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <button type="button" class="rounded-full border border-slate-200 px-2 py-1" @click="moveExisting(idx, -1)">&lsaquo;</button>
                            <button type="button" class="rounded-full border border-slate-200 px-2 py-1" @click="moveExisting(idx, 1)">&rsaquo;</button>
                        </div>
                        <button type="button" class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-rose-200 bg-white text-rose-500 shadow" title="Eliminar" aria-label="Eliminar" @click="removeExisting(idx)">
                            <span class="text-xs font-bold leading-none">x</span>
                        </button>
                    </div>
                </div>
                <div v-for="(img, idx) in previewImages" :key="`preview-${idx}`" class="shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-white" style="width: 80px;">
                    <div class="flex items-center justify-center bg-slate-50" style="height: 56px;">
                        <img :src="img.url" class="h-full w-full object-contain" :style="{ objectPosition: img.position || 'center' }" />
                    </div>
                    <div class="flex flex-col gap-2 border-t border-slate-100 px-2 py-2 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <button type="button" class="rounded-full border border-slate-200 px-2 py-1" @click="movePreview(idx, -1)">&lsaquo;</button>
                            <button type="button" class="rounded-full border border-slate-200 px-2 py-1" @click="movePreview(idx, 1)">&rsaquo;</button>
                        </div>
                        <button type="button" class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-rose-200 bg-white text-rose-500 shadow" title="Eliminar" aria-label="Eliminar" @click="removePreview(idx)">
                            <span class="text-xs font-bold leading-none">x</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input v-model="form.featured" type="checkbox" class="rounded border-slate-300" />
                Destacado
            </label>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input v-model="form.is_active" type="checkbox" class="rounded border-slate-300" />
                Activo
            </label>
        </div>
    </div>
    <ImageCropperModal
        :show="cropperOpen"
        :src="cropSource"
        :mime="cropMime"
        :max-width="1600"
        :max-height="1600"
        v-model:aspectRatio="cropAspect"
        title="Recortar imagen"
        @confirm="handleCropConfirm"
        @cancel="handleCropCancel"
    />
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import ImageCropperModal from '../../Shared/ImageCropperModal.vue';

const props = defineProps({
    form: Object,
    categories: Array,
});

const onFiles = (event) => {
    const files = Array.from(event.target.files || []);
    if (!files.length) {
        return;
    }
    cropAppendOffset.value = previewImages.value.length;
    cropQueue.value = files;
    cropIndex.value = 0;
    cropResults.value = [];
    props.form.images_touched = true;
    startCrop();
};

const addSpec = () => {
    props.form.specs.push({ key: '', value: '' });
};

const removeSpec = (index) => {
    props.form.specs.splice(index, 1);
    if (props.form.specs.length === 0) {
        props.form.specs.push({ key: '', value: '' });
    }
};

const previewImages = ref([]);
const cropQueue = ref([]);
const cropResults = ref([]);
const cropIndex = ref(0);
const cropSource = ref('');
const cropMime = ref('image/jpeg');
const cropName = ref('imagen.jpg');
const cropOriginalFile = ref(null);
const cropperOpen = ref(false);
const cropAspect = ref(4 / 3);
const cropUrl = ref('');
const cropAppendOffset = ref(0);

const imageError = computed(() => {
    if (props.form?.errors?.images) {
        return props.form.errors.images;
    }
    const keys = Object.keys(props.form?.errors || {});
    const match = keys.find((key) => key.startsWith('images.'));
    return match ? props.form.errors[match] : '';
});

const setCropSource = (file) => {
    if (cropUrl.value) {
        URL.revokeObjectURL(cropUrl.value);
    }
    cropUrl.value = URL.createObjectURL(file);
    cropSource.value = cropUrl.value;
    cropName.value = file.name;
    cropMime.value = file.type || 'image/jpeg';
    cropOriginalFile.value = file;
};

const startCrop = () => {
    const file = cropQueue.value[cropIndex.value];
    if (!file) {
        finalizeCrops();
        return;
    }
    setCropSource(file);
    cropperOpen.value = true;
};

const handleCropConfirm = (blob) => {
    if (blob) {
        const file = new File([blob], cropName.value, { type: blob.type || cropMime.value });
        cropResults.value.push(file);
    } else if (cropOriginalFile.value) {
        cropResults.value.push(cropOriginalFile.value);
    }
    nextCrop();
};

const handleCropCancel = () => {
    nextCrop();
};

const nextCrop = () => {
    cropIndex.value += 1;
    if (cropIndex.value >= cropQueue.value.length) {
        finalizeCrops();
        return;
    }
    startCrop();
};

const finalizeCrops = () => {
    cropperOpen.value = false;
    if (cropUrl.value) {
        URL.revokeObjectURL(cropUrl.value);
        cropUrl.value = '';
    }
    cropOriginalFile.value = null;
    const newFiles = cropResults.value;
    const newMeta = newFiles.map(() => ({ position: props.form.image_position || 'center' }));
    const newPreviews = newFiles.map((file, index) => ({
        url: URL.createObjectURL(file),
        position: newMeta[index].position,
    }));
    props.form.images = [...props.form.images, ...newFiles];
    props.form.new_images_meta = [...props.form.new_images_meta, ...newMeta];
    previewImages.value = [...previewImages.value, ...newPreviews];
    props.form.images_touched = true;
};

const moveExisting = (index, delta) => {
    const target = index + delta;
    if (target < 0 || target >= props.form.existing_images.length) {
        return;
    }
    const temp = props.form.existing_images[index];
    props.form.existing_images[index] = props.form.existing_images[target];
    props.form.existing_images[target] = temp;
    props.form.images_touched = true;
};

const movePreview = (index, delta) => {
    const target = index + delta;
    if (target < 0 || target >= previewImages.value.length) {
        return;
    }
    const temp = previewImages.value[index];
    previewImages.value[index] = previewImages.value[target];
    previewImages.value[target] = temp;
    const fileTemp = props.form.images[index];
    props.form.images[index] = props.form.images[target];
    props.form.images[target] = fileTemp;
    props.form.images_touched = true;
};

const removeExisting = (index) => {
    props.form.existing_images.splice(index, 1);
    props.form.images_touched = true;
};

const removePreview = (index) => {
    previewImages.value.splice(index, 1);
    props.form.images.splice(index, 1);
    props.form.new_images_meta.splice(index, 1);
    props.form.images_touched = true;
};

watch(
    () => previewImages.value,
    () => {
        props.form.new_images_meta = previewImages.value.map((img) => ({ position: img.position || 'center' }));
    },
    { deep: true }
);
</script>

