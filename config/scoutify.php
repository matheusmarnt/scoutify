<?php

/**
 * Scoutify v2 configuration — infrastructure only.
 *
 * UI/theme/type customization moved to the fluent API:
 *   Scoutify::types(), Scoutify::theme(), Scoutify::configureUi()
 *
 * See: https://matheusmarnt.github.io/scoutify/upgrading/v2
 */

return [
    'debounce_ms' => 250,
    'recents' => ['enabled' => true, 'limit' => 5, 'storage' => 'session'],
    'discovery' => [
        'paths' => [
            app_path('Models'),
        ],
    ],
    'preview' => [
        'enabled' => true,
        'route_prefix' => 'scoutify/preview',
        'middleware' => ['web'],
        'ttl_seconds' => 300,
        'max_size_bytes' => 50 * 1024 * 1024,
        'allowed_mimes' => [
            'application/pdf',
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            'video/mp4', 'video/webm', 'video/ogg',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'text/plain', 'text/csv',
        ],
        'viewer_for_mime' => [
            'application/pdf' => 'scoutify::components.gs.preview.viewer-pdf',
            'image/*' => 'scoutify::components.gs.preview.viewer-image',
            'video/*' => 'scoutify::components.gs.preview.viewer-video',
        ],
        'fallback_view' => 'scoutify::components.gs.preview.viewer-fallback',
    ],
    'modal' => ['breakpoint_desktop' => 'md'],
    'authorization' => [
        'default' => 'secure', // secure | permissive | gate-only
        'gate_ability' => 'view',
    ],
    'broadcast_events' => [
        'open' => 'scoutify:open',
        'opened' => 'scoutify:opened',
        'closed' => 'scoutify:closed',
        'remember' => 'scoutify:remember',
    ],
];
