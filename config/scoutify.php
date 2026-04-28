<?php

return [
    'icon_prefix' => 'heroicon-o-',
    'recents' => ['enabled' => true, 'limit' => 5, 'storage' => 'session'],
    'debounce_ms' => 250,
    'types' => [
        // 'App\Models\User' => ['icon' => 'user', 'color' => 'indigo', 'label' => 'Usuários'],
    ],
    'classes' => [
        'trigger' => 'group inline-flex h-9 min-w-16 items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-500 transition hover:border-zinc-300 hover:text-zinc-700 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-scoutify-accent/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-200',
        'dialog_scrim' => 'absolute inset-0 bg-zinc-950/50',
        'dialog_panel' => 'relative w-full md:max-w-2xl',
        'input' => 'block w-full rounded-lg border border-zinc-200 bg-white py-2 text-sm text-zinc-900 placeholder-zinc-400 outline-none transition focus-visible:border-zinc-300 focus-visible:ring-2 focus-visible:ring-scoutify-accent/30 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder-zinc-500 [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden',
        'toggle_active' => 'bg-indigo-600 dark:bg-indigo-500',
        'toggle_inactive' => 'bg-zinc-200 dark:bg-zinc-700',
    ],
    'discovery' => [
        'paths' => [
            // app_path('Models'),
        ],
    ],
    'colors' => [
        'zinc' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
        'gray' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
        'blue' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300',
        'red' => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300',
        'green' => 'bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-300',
        'yellow' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
        'amber' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
        'orange' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300',
        'purple' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300',
        'pink' => 'bg-pink-100 text-pink-600 dark:bg-pink-900/40 dark:text-pink-300',
        'indigo' => 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300',
        'teal' => 'bg-teal-100 text-teal-600 dark:bg-teal-900/40 dark:text-teal-300',
    ],
    'modal' => ['breakpoint_desktop' => 'md'],
    'broadcast_events' => [
        'open' => 'scoutify:open',
        'opened' => 'scoutify:opened',
        'closed' => 'scoutify:closed',
        'remember' => 'scoutify:remember',
    ],
];
