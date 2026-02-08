@props(['label' => null, 'type' => 'text', 'name' => null])

<label class="block text-sm font-semibold text-slate-700">
    @if($label)
        <span class="mb-2 inline-block">{{ $label }}</span>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full rounded-xl border-slate-200 bg-white/80 px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-ls-blue focus:ring-ls-blue']) }}
    />
</label>
