<?php

namespace Matheusmarnt\Scoutify\Enums;

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
            self::Red => 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300',
            self::Orange => 'bg-orange-100 text-orange-600 dark:bg-orange-900/40 dark:text-orange-300',
            self::Amber => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
            self::Yellow => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
            self::Lime => 'bg-lime-100 text-lime-700 dark:bg-lime-900/40 dark:text-lime-300',
            self::Green => 'bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-300',
            self::Emerald => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300',
            self::Teal => 'bg-teal-100 text-teal-600 dark:bg-teal-900/40 dark:text-teal-300',
            self::Cyan => 'bg-cyan-100 text-cyan-600 dark:bg-cyan-900/40 dark:text-cyan-300',
            self::Sky => 'bg-sky-100 text-sky-600 dark:bg-sky-900/40 dark:text-sky-300',
            self::Blue => 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300',
            self::Indigo => 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300',
            self::Violet => 'bg-violet-100 text-violet-600 dark:bg-violet-900/40 dark:text-violet-300',
            self::Purple => 'bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300',
            self::Fuchsia => 'bg-fuchsia-100 text-fuchsia-600 dark:bg-fuchsia-900/40 dark:text-fuchsia-300',
            self::Pink => 'bg-pink-100 text-pink-600 dark:bg-pink-900/40 dark:text-pink-300',
            self::Rose => 'bg-rose-100 text-rose-600 dark:bg-rose-900/40 dark:text-rose-300',
            self::Slate => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
            self::Gray => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
            self::Zinc => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
            self::Neutral => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400',
            self::Stone => 'bg-stone-100 text-stone-600 dark:bg-stone-800 dark:text-stone-400',
            self::Taupe => 'bg-taupe-100 text-taupe-600 dark:bg-taupe-800 dark:text-taupe-400',
            self::Mauve => 'bg-mauve-100 text-mauve-600 dark:bg-mauve-900/40 dark:text-mauve-300',
            self::Mist => 'bg-mist-100 text-mist-600 dark:bg-mist-800 dark:text-mist-400',
            self::Olive => 'bg-olive-100 text-olive-700 dark:bg-olive-900/40 dark:text-olive-300',
        };
    }

    public static function resolve(string|self $color): self
    {
        if ($color instanceof self) {
            return $color;
        }

        return self::tryFrom($color) ?? self::Zinc;
    }

    /** Resolves tile classes, falling back to config('scoutify.colors') for custom tokens. */
    public static function resolveClasses(string|self $color): string
    {
        if ($color instanceof self) {
            return $color->tileClasses();
        }

        $enum = self::tryFrom($color);
        if ($enum !== null) {
            return $enum->tileClasses();
        }

        return config("scoutify.colors.{$color}", self::Zinc->tileClasses());
    }
}
