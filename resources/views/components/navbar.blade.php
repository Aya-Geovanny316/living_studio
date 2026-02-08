@php
    $cartItems = session('cart.items', []);
    $cartCount = collect($cartItems)->sum('qty');
@endphp

<header class="bg-white/80 backdrop-blur border-b border-slate-200">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-ls-indigo text-white font-display text-sm">GT</div>
            <div>
                <p class="font-display text-lg text-ls-navy">GT Hobby</p>
                <p class="text-xs text-slate-500">Figuras, escala y kits de coleccion</p>
            </div>
        </a>
        <nav class="hidden items-center gap-6 text-sm font-semibold text-slate-700 md:flex">
            <a href="{{ route('catalog') }}" class="hover:text-ls-blue">Catalogo</a>
            <a href="{{ route('quote.create') }}" class="hover:text-ls-blue">Cotizacion</a>
            <a href="#contacto" class="hover:text-ls-blue">Contacto</a>
        </nav>
        <div class="flex items-center gap-4">
            <a href="{{ route('cart.index') }}" class="relative text-sm font-semibold text-ls-navy hover:text-ls-blue">
                Carrito
                <span class="ml-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-ls-blue text-xs text-white">
                    {{ $cartCount }}
                </span>
            </a>
            @auth
                <!-- Admin acceso solo por URL directa -->
            @endauth
        </div>
    </div>
</header>
