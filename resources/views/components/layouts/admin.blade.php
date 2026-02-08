<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Admin | GT Hobby' }}</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-shell">
        <div class="flex min-h-screen">
            <aside class="admin-sidebar w-64 text-white">
                <div class="px-6 py-6">
                    <p class="font-display text-lg text-white">GT Hobby</p>
                    <p class="mt-2 text-xs text-white/70">Panel Admin</p>
                </div>
                <nav class="space-y-1 px-4 text-sm">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M3 12l9-9 9 9"></path>
                            <path d="M9 21V9h6v12"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 7H4"></path>
                            <path d="M20 12H4"></path>
                            <path d="M20 17H4"></path>
                            <circle cx="8" cy="7" r="1"></circle>
                            <circle cx="8" cy="12" r="1"></circle>
                            <circle cx="8" cy="17" r="1"></circle>
                        </svg>
                        Productos
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 4h7v7H4z"></path>
                            <path d="M13 4h7v7h-7z"></path>
                            <path d="M4 13h7v7H4z"></path>
                            <path d="M13 13h7v7h-7z"></path>
                        </svg>
                        Categorias
                    </a>
                    <a href="{{ route('admin.promotions.index') }}" class="admin-nav-link {{ request()->routeIs('admin.promotions.*') ? 'is-active' : '' }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 6h16v12H4z"></path>
                            <path d="M8 10h8"></path>
                            <path d="M8 14h5"></path>
                        </svg>
                        Promociones
                    </a>
                    <a href="{{ route('admin.quotes.index') }}" class="admin-nav-link {{ request()->routeIs('admin.quotes.*') ? 'is-active' : '' }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 4h16v16H4z"></path>
                            <path d="M8 9h8"></path>
                            <path d="M8 13h8"></path>
                            <path d="M8 17h5"></path>
                        </svg>
                        Cotizaciones
                    </a>
                </nav>
            </aside>
            <div class="flex-1">
                <header class="admin-header flex items-center justify-between px-8 py-4">
                    <div>
                        <h1 class="font-display text-xl text-ls-navy">{{ $title ?? 'Dashboard' }}</h1>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-slate-600">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-full bg-ls-blue px-4 py-2 text-xs font-semibold text-white">Salir</button>
                        </form>
                    </div>
                </header>
                <main class="px-8 py-8">
                    <x-flash />
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
