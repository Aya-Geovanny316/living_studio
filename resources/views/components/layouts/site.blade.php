<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'GT Hobby' }}</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-ls-light">
        <x-navbar />
        <main>
            {{ $slot }}
        </main>
        <footer id="contacto" class="border-t border-slate-200 bg-white/80">
            <div class="mx-auto grid max-w-6xl gap-8 px-6 py-12 md:grid-cols-3">
                <div>
                    <p class="font-display text-lg text-ls-navy">GT Hobby</p>
                    <p class="mt-2 text-sm text-slate-600">Catalogo moderno de modelismo, juguetes a escala y hobbies creativos.</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700">Contacto</p>
                    <p class="mt-2 text-sm text-slate-600">hola@gthobby.com</p>
                    <p class="text-sm text-slate-600">+502 4000 0000</p>
                    <p class="text-sm text-slate-600">Ciudad de Guatemala, Guatemala</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700">Links</p>
                    <div class="mt-2 flex flex-col gap-2 text-sm text-slate-600">
                        <a href="{{ route('catalog') }}" class="hover:text-ls-blue">Catalogo</a>
                        <a href="{{ route('quote.create') }}" class="hover:text-ls-blue">Cotizacion</a>
                        <a href="{{ route('cart.index') }}" class="hover:text-ls-blue">Carrito</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-200 py-4 text-center text-xs text-slate-500">
                2026 GT Hobby. Todos los derechos reservados.
            </div>
        </footer>
    </body>
</html>
