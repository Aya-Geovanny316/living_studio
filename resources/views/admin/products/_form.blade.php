@csrf
<x-form-errors />
<div class="grid gap-6 md:grid-cols-2">
    <x-input name="name" label="Nombre" value="{{ old('name', $product->name ?? '') }}" />
    <div>
        <label class="block text-sm font-semibold text-slate-700">Categoria</label>
        <select name="category_id" class="mt-2 w-full rounded-xl border-slate-200 bg-white/90 text-sm">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <x-input name="price_estimate" label="Precio estimado" value="{{ old('price_estimate', $product->price_estimate ?? '') }}" />
    <x-input name="sku" label="SKU" value="{{ old('sku', $product->sku ?? '') }}" />
    <x-input name="stock_status" label="Estado de stock" value="{{ old('stock_status', $product->stock_status ?? '') }}" />
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700">Fabricante</label>
        <input name="short_description" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm" value="{{ old('short_description', $product->short_description ?? '') }}">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700">Descripcion completa</label>
        <textarea name="description" rows="4" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm">{{ old('description', $product->description ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700">Specs (JSON)</label>
        <textarea name="specs_json" rows="3" class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-xs">{{ old('specs_json', isset($product) && $product->specs ? json_encode($product->specs, JSON_PRETTY_PRINT) : '') }}</textarea>
        <p class="mt-1 text-xs text-slate-500">Ejemplo: {"Control":"App","Compatibilidad":"Alexa"}</p>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700">Imagenes</label>
        <input type="file" name="images[]" multiple class="mt-2 w-full rounded-xl border-slate-200 bg-white/80 text-sm">
    </div>
    <div class="flex items-center gap-6">
        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="featured" value="1" class="rounded border-slate-300" @checked(old('featured', $product->featured ?? false))>
            Destacado
        </label>
        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" @checked(old('is_active', $product->is_active ?? true))>
            Activo
        </label>
    </div>
</div>
<div class="mt-6">
    <x-button variant="dark">{{ $button }}</x-button>
</div>
