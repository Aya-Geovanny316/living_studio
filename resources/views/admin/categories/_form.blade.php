@csrf
<x-form-errors />
<div class="grid gap-6 md:grid-cols-2">
    <x-input name="name" label="Nombre" value="{{ old('name', $category->name ?? '') }}" />
    <x-input name="icon" label="Icono (opcional)" value="{{ old('icon', $category->icon ?? '') }}" />
    <x-input name="sort_order" label="Orden" value="{{ old('sort_order', $category->sort_order ?? 0) }}" />
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" @checked(old('is_active', $category->is_active ?? true))>
        <label class="text-sm text-slate-600">Activo</label>
    </div>
</div>
<div class="mt-6">
    <x-button variant="dark">{{ $button }}</x-button>
</div>
