@php
    $title = 'Catalogo | GT Hobby';
@endphp

<x-layouts.site :title="$title">
    <section class="toy-shelf">
        <div class="mx-auto max-w-6xl px-6 py-12">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-ls-ink">Catalogo GT Hobby</p>
                    <h1 class="mt-2 font-display text-3xl text-ls-navy md:text-4xl">Figuras, modelos y kits</h1>
                    <p class="mt-2 text-sm text-slate-600">Curado para coleccionistas y creadores.</p>
                </div>
                <a href="{{ route('quote.create') }}" class="rounded-full bg-ls-indigo px-5 py-2 text-xs font-semibold text-white">Cotizar mi carrito</a>
            </div>

            <x-flash />

            <form method="GET" action="{{ route('catalog') }}" class="toy-panel mt-8 grid gap-4 p-6 md:grid-cols-4">
                <div>
                    <label class="text-xs font-semibold uppercase text-slate-500">Categoria</label>
                    <select name="category" class="mt-2 w-full rounded-xl border-slate-200 bg-white/90 text-sm">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase text-slate-500">Busqueda</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="mt-2 w-full rounded-xl border-slate-200 bg-white/90 text-sm" placeholder="Ej: figuras 1:12, Gundam, Marvel">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase text-slate-500">Ordenar</label>
                    <select name="sort" class="mt-2 w-full rounded-xl border-slate-200 bg-white/90 text-sm">
                        <option value="">Mas nuevos</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Precio: menor a mayor</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Precio: mayor a menor</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <x-button type="submit" variant="dark" class="w-full">Filtrar</x-button>
                </div>
            </form>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @forelse($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="col-span-full rounded-2xl bg-white p-8 text-center text-slate-500">No hay productos con esos filtros.</div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $products->links() }}
            </div>
        </div>
    </section>
</x-layouts.site>
