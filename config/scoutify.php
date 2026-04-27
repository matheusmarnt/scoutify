<?php

return [
    'icon_prefix' => 'heroicon-o-',
    'recents' => ['enabled' => true, 'limit' => 5, 'storage' => 'session'],
    'debounce_ms' => 250,
    'types' => [
        // 'App\Models\User' => ['icon' => 'user', 'color' => 'indigo', 'label' => 'Usuários'],
    ],
    'classes' => [
        'trigger' => 'group inline-flex h-9 min-w-16 items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-500 transition hover:border-zinc-300 hover:text-zinc-700 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-accent/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-200',
        'dialog_scrim' => 'absolute inset-0 bg-zinc-950/50',
        'dialog_panel' => 'flex max-h-[90dvh] min-h-0 flex-col overflow-hidden rounded-t-2xl bg-white pb-[env(safe-area-inset-bottom)] md:max-h-[80vh] md:rounded-xl md:shadow-2xl md:ring-1 md:ring-zinc-900/5 dark:bg-zinc-900 dark:md:ring-white/10',
        'input' => 'block w-full rounded-lg border border-zinc-200 bg-white py-2 text-sm text-zinc-900 placeholder-zinc-400 outline-none transition focus-visible:border-zinc-300 focus-visible:ring-2 focus-visible:ring-accent/30 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder-zinc-500 [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden',
        'toggle_active' => 'bg-indigo-600 dark:bg-indigo-500',
        'toggle_inactive' => 'bg-zinc-200 dark:bg-zinc-700',
    ],
    'modal' => ['breakpoint_desktop' => 'md'],
    'broadcast_events' => [
        'open' => 'scoutify:open',
        'opened' => 'scoutify:opened',
        'closed' => 'scoutify:closed',
        'remember' => 'scoutify:remember',
    ],
];
