@csrf
<x-form-errors />
<div class="grid gap-6 md:grid-cols-2">
    <x-input name="title" label="Titulo" value="{{ old('title', $promotion->title ?? '') }}" />
    <x-input name="subtitle" label="Subtitulo" value="{{ old('subtitle', $promotion->subtitle ?? '') }}" />
    <x-input name="link" label="Link (opcional)" value="{{ old('link', $promotion->link ?? '') }}" />
    <x-input name="sort_order" label="Orden" value="{{ old('sort_order', $promotion->sort_order ?? 0) }}" />
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700">Imagen</label>
        <input type="file" name="image" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 text-sm">
        @if(!empty($promotion->image_path))
            <img src="{{ $promotion->image_path }}" alt="{{ $promotion->title }}" class="mt-4 h-32 rounded-xl object-cover">
        @endif
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" @checked(old('is_active', $promotion->is_active ?? true))>
        <label class="text-sm text-slate-600">Activo</label>
    </div>
</div>
<div class="mt-6">
    <x-button variant="dark">{{ $button }}</x-button>
</div>
