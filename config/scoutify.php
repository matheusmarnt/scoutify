<?php

return [
    'icon_prefix' => 'heroicon-o-',
    'recents' => ['enabled' => true, 'limit' => 5, 'storage' => 'session'],
    'debounce_ms' => 250,
    'types' => [
        // 'App\Models\User' => ['icon' => 'user', 'color' => 'indigo', 'label' => 'Users'],
    ],
    'classes' => [
        'trigger' => 'group inline-flex h-9 min-w-16 items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-500 transition hover:border-zinc-300 hover:text-zinc-700 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-scoutify-accent/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-200',
        'trigger_mobile' => 'lg:hidden inline-flex size-11 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 transition hover:border-zinc-300 hover:text-zinc-700 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-scoutify-accent/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-200',
        'dialog_scrim' => 'absolute inset-0 bg-zinc-950/50',
        'dialog_panel' => 'relative w-full md:max-w-2xl',
        'input' => 'block w-full rounded-lg border border-zinc-200 bg-white py-2 text-sm text-zinc-900 placeholder-zinc-400 outline-none transition focus-visible:border-zinc-300 focus-visible:ring-2 focus-visible:ring-scoutify-accent/30 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder-zinc-500 [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden',
        'toggle_active' => 'bg-indigo-600 dark:bg-indigo-500',
        'toggle_inactive' => 'bg-zinc-200 dark:bg-zinc-700',
    ],
    'discovery' => [
        'paths' => [
            app_path('Models'),
        ],
    ],
    // Extension point for custom color tokens not in Matheusmarnt\Scoutify\Enums\Color.
    // Built-in TailwindCSS v4 colors are handled by the enum automatically.
    // Example: 'coral' => 'bg-coral-100 text-coral-600 dark:bg-coral-900/40 dark:text-coral-300'
    'colors' => [
        //
    ],
    'modal' => ['breakpoint_desktop' => 'md'],
    'broadcast_events' => [
        'open' => 'scoutify:open',
        'opened' => 'scoutify:opened',
        'closed' => 'scoutify:closed',
        'remember' => 'scoutify:remember',
    ],
];
