<?php

namespace Matheusmarnt\Scoutify\Authorization;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class VisibilityRule
{
    protected bool $allowGuests = false;

    /** @var array<Closure> */
    protected array $rules = [];

    protected VisibilityMode $mode = VisibilityMode::Any;

    public static function make(): self
    {
        return new static;
    }

    public function visibleToGuests(bool $allow = true): self
    {
        $this->allowGuests = $allow;

        return $this;
    }

    public function whenAuthenticated(): self
    {
        return $this;
    }

    public function orWhenAuthenticated(): self
    {
        return $this;
    }

    public function policy(string $ability, mixed ...$args): self
    {
        return $this->using(fn (Model $record, Authenticatable $user) => Gate::forUser($user)->check($ability, $record, ...$args));
    }

    public function permission(string|array $perm, ?string $guard = null): self
    {
        return $this->using(function (Model $record, Authenticatable $user) use ($perm, $guard) {
            if (! method_exists($user, 'hasPermissionTo')) {
                return false;
            }

            return is_array($perm)
                ? $user->hasAnyPermission($perm, $guard)
                : $user->hasPermissionTo($perm, $guard);
        });
    }

    public function orPermission(string|array $perm, ?string $guard = null): self
    {
        return $this->permission($perm, $guard);
    }

    public function role(string|array $role, ?string $guard = null): self
    {
        return $this->using(function (Model $record, Authenticatable $user) use ($role, $guard) {
            if (! method_exists($user, 'hasRole')) {
                return false;
            }

            return is_array($role)
                ? $user->hasAnyRole($role, $guard)
                : $user->hasRole($role, $guard);
        });
    }

    public function orRole(string|array $role, ?string $guard = null): self
    {
        return $this->role($role, $guard);
    }

    public function attribute(string $name, mixed $expected = true): self
    {
        return $this->using(fn (Model $record) => $record->{$name} == $expected);
    }

    public function orAttribute(string $name, mixed $expected = true): self
    {
        return $this->attribute($name, $expected);
    }

    public function using(Closure $cb): self
    {
        $this->rules[] = $cb;

        return $this;
    }

    public function mode(VisibilityMode $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function evaluate(Model $record, ?Authenticatable $user): bool
    {
        if ($user === null) {
            return $this->allowGuests;
        }

        if (empty($this->rules)) {
            return false;
        }

        if ($this->mode === VisibilityMode::All) {
            foreach ($this->rules as $rule) {
                if (! $rule($record, $user)) {
                    return false;
                }
            }

            return true;
        }

        foreach ($this->rules as $rule) {
            if ($rule($record, $user)) {
                return true;
            }
        }

        return false;
    }
}
