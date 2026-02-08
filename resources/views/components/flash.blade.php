@if(session('success'))
    <div class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 rounded-xl bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
        {{ session('error') }}
    </div>
@endif
