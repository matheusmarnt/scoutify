@props([
    'hints' => [
        ['key' => '↑↓', 'label' => 'navegar'],
        ['key' => '↵',  'label' => 'abrir'],
        ['key' => 'esc','label' => 'fechar'],
    ],
])

{{-- Desktop --}}
<div class="hidden shrink-0 items-center justify-between border-t border-zinc-100 bg-gradient-to-b from-transparent to-zinc-50/40 px-3.5 py-2 sm:flex dark:border-zinc-800 dark:to-zinc-900/40">
    <div class="flex items-center gap-3 text-[11px] text-zinc-500 dark:text-zinc-400">
        @foreach ($hints as $h)
            <span class="flex items-center gap-1">
                <x-scoutify::gs.kbd>{{ $h['key'] }}</x-scoutify::gs.kbd>
                {{ __($h['label']) }}
            </span>
        @endforeach
    </div>
    @if (isset($trailing))
        <div>{{ $trailing }}</div>
    @endif
</div>

{{-- Mobile --}}
<div class="flex shrink-0 items-center justify-end border-t border-zinc-100 px-3 py-2 sm:hidden dark:border-zinc-800">
    <span class="flex items-center gap-1 text-[11px] text-zinc-500 dark:text-zinc-400">
        <x-scoutify::gs.kbd>esc</x-scoutify::gs.kbd>
        {{ __('fechar') }}
    </span>
</div>
