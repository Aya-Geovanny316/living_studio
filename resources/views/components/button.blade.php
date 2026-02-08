@props(['variant' => 'primary', 'type' => 'submit'])

@php
    $base = 'inline-flex items-center justify-center rounded-full px-6 py-3 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2';
    $variants = [
        'primary' => 'bg-ls-indigo text-white hover:brightness-95 focus:ring-ls-indigo',
        'dark' => 'bg-ls-navy text-white hover:brightness-110 focus:ring-ls-navy',
        'light' => 'bg-white text-ls-navy ring-1 ring-slate-200 hover:bg-slate-50',
        'ghost' => 'text-ls-navy hover:text-ls-indigo',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $base . ' ' . ($variants[$variant] ?? $variants['primary'])]) }}>
    {{ $slot }}
</button>
