@php
    $flashMessages = [
        'success' => session('success'),
        'error' => session('error'),
        'warning' => session('warning'),
        'info' => session('info'),
    ];
@endphp

@foreach ($flashMessages as $type => $message)
    @if ($message)
        @php
            $flashStyles = [
                'success' => ['border-emerald-200', 'bg-emerald-50', 'text-emerald-700', 'bg-emerald-500', 'check_circle'],
                'error' => ['border-rose-200', 'bg-rose-50', 'text-rose-700', 'bg-rose-500', 'error'],
                'warning' => ['border-amber-200', 'bg-amber-50', 'text-amber-700', 'bg-amber-500', 'warning'],
                'info' => ['border-blue-200', 'bg-blue-50', 'text-blue-700', 'bg-blue-500', 'info'],
            ][$type];
        @endphp

        <div class="fixed left-1/2 top-5 z-[200] flex w-[calc(100%-2rem)] max-w-md -translate-x-1/2 items-start gap-3 rounded-2xl border bg-white px-4 py-3.5 text-sm shadow-lg shadow-slate-900/10 {{ $flashStyles[0] }} {{ $flashStyles[2] }}" role="alert">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $flashStyles[1] }}">
                <span class="material-icons-outlined text-base">{{ $flashStyles[4] }}</span>
            </span>
            <span class="flex-1 pt-1 font-semibold leading-5">{{ $message }}</span>
            <button type="button" onclick="this.closest('[role=alert]').remove()" class="rounded-lg p-1 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup notifikasi">
                <span class="material-icons-outlined text-base">close</span>
            </button>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="fixed left-1/2 top-5 z-[200] flex w-[calc(100%-2rem)] max-w-md -translate-x-1/2 items-start gap-3 rounded-2xl border border-rose-200 bg-white px-4 py-3.5 text-sm text-rose-700 shadow-lg shadow-slate-900/10" role="alert">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-rose-50">
            <span class="material-icons-outlined text-base">error</span>
        </span>
        <ul class="flex-1 space-y-1 pt-1 font-semibold leading-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" onclick="this.closest('[role=alert]').remove()" class="rounded-lg p-1 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup notifikasi">
            <span class="material-icons-outlined text-base">close</span>
        </button>
    </div>
@endif

<script>
    document.querySelectorAll('[role="alert"]').forEach((alert) => {
        setTimeout(() => alert.remove(), 5000);
    });
</script>
