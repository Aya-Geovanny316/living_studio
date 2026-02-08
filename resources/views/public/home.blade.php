@php
    $title = 'GT Hobby';
@endphp

<x-layouts.site :title="$title">
    <section class="relative overflow-hidden toy-hero">
        <div class="absolute inset-0 opacity-30">
            <div class="h-full w-full bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.25),transparent_40%),radial-gradient(circle_at_80%_30%,rgba(255,255,255,0.18),transparent_45%)]"></div>
        </div>
        <div class="relative mx-auto grid max-w-6xl gap-10 px-6 py-24 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <div class="text-white">
                <span class="toy-sticker">Coleccionables premium</span>
                <h1 class="mt-6 font-display text-4xl md:text-5xl">El mundo del hobby en una sola vitrina</h1>
                <p class="mt-6 text-lg text-white/90">
                    Figuras de accion, modelos a escala, herramientas y kits para constructores exigentes.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('catalog') }}" class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-ls-navy">Explorar catalogo</a>
                    <a href="{{ route('quote.create') }}" class="rounded-full border border-white/60 px-6 py-3 text-sm font-semibold text-white">Pedir cotizacion</a>
                </div>
                <div class="mt-10 flex flex-wrap gap-4 text-sm text-white/80">
                    <div class="toy-panel px-4 py-3 text-ls-navy">
                        <p class="text-xs uppercase tracking-[0.3em] text-ls-ink">Envios</p>
                        <p class="mt-1 font-semibold">Rapidos y seguros</p>
                    </div>
                    <div class="toy-panel px-4 py-3 text-ls-navy">
                        <p class="text-xs uppercase tracking-[0.3em] text-ls-ink">Asesoria</p>
                        <p class="mt-1 font-semibold">Equipo experto</p>
                    </div>
                </div>
            </div>
            <div class="toy-panel p-6 text-ls-navy">
                <p class="text-xs uppercase tracking-[0.3em] text-ls-ink">Novedades</p>
                <h2 class="mt-3 font-display text-2xl">Lanzamientos de temporada</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Preventas exclusivas y piezas limitadas para coleccionistas.
                </p>
                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between rounded-2xl bg-ls-light/70 px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold">Series premium 1:12</p>
                            <p class="text-xs text-slate-500">Entrega estimada 3 semanas</p>
                        </div>
                        <span class="text-xs font-semibold text-ls-indigo">Preventa</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-ls-light/70 px-4 py-3">
                        <div>
                            <p class="text-sm font-semibold">Kits de armado Pro</p>
                            <p class="text-xs text-slate-500">Herramientas incluidas</p>
                        </div>
                        <span class="text-xs font-semibold text-ls-indigo">Nuevo</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-section title="Categorias" subtitle="Explora la vitrina">
        <div class="grid gap-6 md:grid-cols-4">
            @foreach($categories as $category)
                <a href="{{ route('catalog', ['category' => $category->slug]) }}" class="toy-card p-6">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-ls-indigo text-white">
                        {{ strtoupper(substr($category->name, 0, 2)) }}
                    </div>
                    <p class="font-display text-lg text-ls-navy">{{ $category->name }}</p>
                    <p class="mt-2 text-sm text-slate-600">Encuentra lo que buscas en minutos.</p>
                </a>
            @endforeach
        </div>
    </x-section>

    <x-section title="Destacados" subtitle="Coleccion premium">
        <div class="grid gap-6 md:grid-cols-3">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </x-section>

    <x-section title="Promociones" subtitle="Novedades y bundles">
        <div class="grid gap-6 md:grid-cols-2">
            @foreach($promotions as $promo)
                <div class="relative overflow-hidden rounded-3xl bg-ls-navy p-8 text-white shadow-xl">
                    @if($promo->image_path)
                        <div class="absolute inset-0 opacity-20">
                            <img src="{{ $promo->image_path }}" alt="{{ $promo->title }}" class="h-full w-full object-cover">
                        </div>
                    @endif
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">GT Hobby</p>
                        <h3 class="mt-3 font-display text-2xl">{{ $promo->title }}</h3>
                        <p class="mt-2 text-sm text-white/80">{{ $promo->subtitle }}</p>
                        @if($promo->link)
                            <a href="{{ $promo->link }}" class="mt-4 inline-flex rounded-full bg-white px-4 py-2 text-xs font-semibold text-ls-navy">Ver mas</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </x-section>
</x-layouts.site>
