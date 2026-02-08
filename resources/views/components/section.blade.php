@props(['title' => null, 'subtitle' => null])

<section {{ $attributes->merge(['class' => 'ls-section']) }}>
    <div class="mx-auto max-w-6xl px-6">
        @if($title)
            <div class="mb-10">
                <p class="text-xs uppercase tracking-[0.3em] text-ls-blue">{{ $subtitle }}</p>
                <h2 class="mt-3 font-display text-3xl text-ls-navy md:text-4xl">{{ $title }}</h2>
            </div>
        @endif
        {{ $slot }}
    </div>
</section>
