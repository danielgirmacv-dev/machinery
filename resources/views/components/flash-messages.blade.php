@if(session('success'))
    <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
