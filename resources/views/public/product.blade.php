@php
    $title = $product->name . ' | GT Hobby';
@endphp

<x-layouts.site :title="$title">
    <section class="toy-shelf">
        <div class="mx-auto max-w-6xl px-6 py-12">
            <div class="mb-6 text-xs uppercase tracking-[0.3em] text-ls-ink">
                <a href="{{ route('catalog') }}" class="hover:text-ls-indigo">Catalogo</a>
                <span class="mx-2">/</span>
                <span>{{ $product->category?->name }}</span>
            </div>
            <div class="grid gap-10 lg:grid-cols-2">
                <div class="space-y-4">
                    <div class="overflow-hidden rounded-3xl bg-white shadow-xl">
                        @if(!empty($product->images))
                            <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="h-96 w-full object-cover">
                        @else
                            <div class="flex h-96 items-center justify-center text-slate-400">Sin imagen</div>
                        @endif
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach(($product->images ?? []) as $image)
                            <div class="overflow-hidden rounded-2xl bg-white">
                                <img src="{{ $image }}" alt="{{ $product->name }}" class="h-24 w-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <span class="toy-sticker">{{ $product->category?->name }}</span>
                    <h1 class="mt-4 font-display text-3xl text-ls-navy md:text-4xl">{{ $product->name }}</h1>
                    <p class="mt-4 text-lg text-slate-600">{{ $product->description ?? $product->short_description }}</p>

                    <div class="toy-panel mt-6 flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-ls-ink">Precio estimado</p>
                            <span class="mt-2 block text-2xl font-semibold text-ls-blue">Q {{ number_format($product->price_estimate, 2, '.', ',') }}</span>
                        </div>
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <x-button variant="dark">Agregar al carrito</x-button>
                        </form>
                    </div>

                    @if(!empty($product->specs))
                        <div class="mt-8">
                            <h3 class="font-display text-xl text-ls-navy">Detalles tecnicos</h3>
                            <dl class="mt-4 grid gap-3 rounded-2xl bg-white p-6 text-sm text-slate-600">
                                @foreach($product->specs as $label => $value)
                                    <div class="flex justify-between border-b border-slate-100 pb-2">
                                        <dt class="font-semibold text-slate-700">{{ $label }}</dt>
                                        <dd>{{ $value }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if($related->count())
        <x-section title="Productos relacionados" subtitle="Completa tu coleccion">
            <div class="grid gap-6 md:grid-cols-4">
                @foreach($related as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </x-section>
    @endif
</x-layouts.site>
