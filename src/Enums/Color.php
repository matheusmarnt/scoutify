<?php

namespace Matheusmarnt\Scoutify\Enums;

use Matheusmarnt\Scoutify\ScoutifyManager;

enum Color: string
{
    // Chromatic
    case Red = 'red';
    case Orange = 'orange';
    case Amber = 'amber';
    case Yellow = 'yellow';
    case Lime = 'lime';
    case Green = 'green';
    case Emerald = 'emerald';
    case Teal = 'teal';
    case Cyan = 'cyan';
    case Sky = 'sky';
    case Blue = 'blue';
    case Indigo = 'indigo';
    case Violet = 'violet';
    case Purple = 'purple';
    case Fuchsia = 'fuchsia';
    case Pink = 'pink';
    case Rose = 'rose';

    // Neutral
    case Slate = 'slate';
    case Gray = 'gray';
    case Zinc = 'zinc';
    case Neutral = 'neutral';
    case Stone = 'stone';

    // New in Tailwind v4
    case Taupe = 'taupe';
    case Mauve = 'mauve';
    case Mist = 'mist';
    case Olive = 'olive';

    public function tileClasses(): string
    {
        return match ($this) {
            self::Red => 'bg-red-100 text-red-600 dark:bg-red-900/60 dark:text-red-200',
            self::Orange => 'bg-orange-100 text-orange-600 dark:bg-orange-900/60 dark:text-orange-200',
            self::Amber => 'bg-amber-100 text-amber-700 dark:bg-amber-900/60 dark:text-amber-200',
            self::Yellow => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/60 dark:text-yellow-200',
            self::Lime => 'bg-lime-100 text-lime-700 dark:bg-lime-900/60 dark:text-lime-200',
            self::Green => 'bg-green-100 text-green-600 dark:bg-green-900/60 dark:text-green-200',
            self::Emerald => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/60 dark:text-emerald-200',
            self::Teal => 'bg-teal-100 text-teal-600 dark:bg-teal-900/60 dark:text-teal-200',
            self::Cyan => 'bg-cyan-100 text-cyan-600 dark:bg-cyan-900/60 dark:text-cyan-200',
            self::Sky => 'bg-sky-100 text-sky-600 dark:bg-sky-900/60 dark:text-sky-200',
            self::Blue => 'bg-blue-100 text-blue-600 dark:bg-blue-900/60 dark:text-blue-200',
            self::Indigo => 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/60 dark:text-indigo-200',
            self::Violet => 'bg-violet-100 text-violet-600 dark:bg-violet-900/60 dark:text-violet-200',
            self::Purple => 'bg-purple-100 text-purple-600 dark:bg-purple-900/60 dark:text-purple-200',
            self::Fuchsia => 'bg-fuchsia-100 text-fuchsia-600 dark:bg-fuchsia-900/60 dark:text-fuchsia-200',
            self::Pink => 'bg-pink-100 text-pink-600 dark:bg-pink-900/60 dark:text-pink-200',
            self::Rose => 'bg-rose-100 text-rose-600 dark:bg-rose-900/60 dark:text-rose-200',
            self::Slate => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
            self::Gray => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
            self::Zinc => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
            self::Neutral => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400',
            self::Stone => 'bg-stone-100 text-stone-600 dark:bg-stone-800 dark:text-stone-400',
            self::Taupe => 'bg-taupe-100 text-taupe-600 dark:bg-taupe-800 dark:text-taupe-400',
            self::Mauve => 'bg-mauve-100 text-mauve-600 dark:bg-mauve-900/60 dark:text-mauve-200',
            self::Mist => 'bg-mist-100 text-mist-600 dark:bg-mist-800 dark:text-mist-400',
            self::Olive => 'bg-olive-100 text-olive-700 dark:bg-olive-900/60 dark:text-olive-200',
        };
    }

    public static function resolve(string|self $color): self
    {
        if ($color instanceof self) {
            return $color;
        }

        return self::tryFrom($color) ?? self::Zinc;
    }

    /** Resolves tile classes, checking ThemeConfig custom colors first, then falling back to enum defaults. */
    public static function resolveClasses(string|self $color): string
    {
        if ($color instanceof self) {
            return $color->tileClasses();
        }

        $enum = self::tryFrom($color);

        if (app()->bound(ScoutifyManager::class)) {
            $override = app(ScoutifyManager::class)->theme()->resolveColorClasses($color);
            if ($override !== null) {
                return $override;
            }
        }

        if ($enum !== null) {
            return $enum->tileClasses();
        }

        return self::Zinc->tileClasses();
    }
}
