@props(['product'])

<div {{ $attributes->merge(['class' => 'toy-card']) }}>
    <div class="aspect-[4/3] bg-gradient-to-br from-white via-slate-50 to-slate-100">
        @if(!empty($product->images))
            <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
        @else
            <div class="flex h-full items-center justify-center text-slate-400">Sin imagen</div>
        @endif
    </div>
    <div class="p-5">
        <div class="flex items-center justify-between text-xs uppercase tracking-wide text-slate-500">
            <span>{{ $product->category?->name }}</span>
            <span class="rounded-full bg-ls-indigo/10 px-2 py-1 text-[10px] font-semibold text-ls-indigo">Nuevo</span>
        </div>
        <h3 class="mt-2 font-display text-lg text-ls-navy">{{ $product->name }}</h3>
        <p class="mt-2 text-sm text-slate-600">{{ $product->short_description }}</p>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-sm font-semibold text-ls-blue">Q {{ number_format($product->price_estimate, 2, '.', ',') }}</span>
            <a href="{{ route('product.show', $product->slug) }}" class="text-sm font-semibold text-ls-navy hover:text-ls-blue">Ver detalle</a>
        </div>
    </div>
</div>
