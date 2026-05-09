<?php

namespace Matheusmarnt\Scoutify\Support;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Component;
use ReflectionClass;

final class SlotResolver
{
    public static function render(mixed $slot, SlotContext $ctx): HtmlString
    {
        return match (true) {
            $slot === null => new HtmlString(''),

            is_string($slot) && class_exists($slot) && is_subclass_of($slot, Component::class) => new HtmlString(Blade::renderComponent(new $slot(...self::propsFor($slot, $ctx)))),

            is_string($slot) => new HtmlString(view($slot, $ctx->toArray())->render()),

            $slot instanceof \Closure => self::wrapClosureResult($slot($ctx)),

            default => throw new \InvalidArgumentException(
                'Slot must be a view name (string), Closure, or Blade Component class-string.'
            ),
        };
    }

    /**
     * @return array<string, mixed>
     */
    private static function propsFor(string $class, SlotContext $ctx): array
    {
        $constructor = (new ReflectionClass($class))->getConstructor();
        if ($constructor === null) {
            return [];
        }

        $ctxArray = $ctx->toArray();
        $props = [];

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            if (array_key_exists($name, $ctxArray)) {
                $props[$name] = $ctxArray[$name];
            }
        }

        return $props;
    }

    private static function wrapClosureResult(mixed $result): HtmlString
    {
        if ($result instanceof HtmlString) {
            return $result;
        }

        if ($result instanceof View) {
            return new HtmlString($result->render());
        }

        return new HtmlString((string) $result);
    }
}
