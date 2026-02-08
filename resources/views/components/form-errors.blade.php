@if($errors->any())
    <div class="mb-6 rounded-xl bg-rose-50 px-4 py-3 text-sm text-rose-700">
        <ul class="list-disc space-y-1 pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
