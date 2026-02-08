@php
    $title = 'Mi cuenta';
@endphp

<x-layouts.customer :title="$title">
    <div class="grid gap-8 lg:grid-cols-3">
        <div class="toy-panel p-6 lg:col-span-1">
            <div class="flex items-center gap-2 text-xs uppercase tracking-[0.3em] text-ls-ink">
                <svg class="h-4 w-4 text-ls-indigo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Perfil
            </div>
            <h2 class="mt-3 font-display text-xl text-ls-navy">{{ $user->name }}</h2>
            <p class="mt-2 text-sm text-slate-600">{{ $user->email }}</p>
            <p class="text-sm text-slate-600">{{ $user->phone }}</p>
            <a href="{{ route('profile.edit') }}" class="mt-4 inline-flex text-sm font-semibold text-ls-indigo">Editar perfil</a>
        </div>
        <div class="lg:col-span-2">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-ls-indigo" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 4h16v16H4z"></path>
                    <path d="M8 9h8"></path>
                    <path d="M8 13h8"></path>
                    <path d="M8 17h5"></path>
                </svg>
                <h3 class="font-display text-xl text-ls-navy">Cotizaciones recientes</h3>
            </div>
            <div class="mt-4 space-y-4">
                @forelse($quotes as $quote)
                    <div class="toy-panel flex items-center justify-between px-6 py-4">
                        <div>
                            <p class="text-sm font-semibold text-ls-navy">{{ $quote->quote_number }}</p>
                            <p class="text-xs text-slate-500">{{ $quote->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <x-badge tone="info">{{ $quote->status }}</x-badge>
                            <p class="mt-2 text-sm font-semibold text-ls-blue">Q {{ number_format($quote->subtotal_estimate, 2, '.', ',') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="toy-panel p-6">
                        <p class="text-sm text-slate-500">Aun no has enviado cotizaciones.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-6">
                {{ $quotes->links() }}
            </div>
        </div>
    </div>
</x-layouts.customer>
