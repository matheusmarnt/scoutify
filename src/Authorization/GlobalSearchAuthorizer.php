<?php

namespace Matheusmarnt\Scoutify\Authorization;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Matheusmarnt\Scoutify\Contracts\HasGlobalSearchVisibility;

class GlobalSearchAuthorizer
{
    public function authorize(Model $record, ?Authenticatable $user): bool
    {
        if ($record instanceof HasGlobalSearchVisibility) {
            return $record->globalSearchVisibility()->evaluate($record, $user);
        }

        $defaultMode = config('scoutify.authorization.default', 'secure');
        $gateAbility = config('scoutify.authorization.gate_ability', 'view');

        if ($defaultMode === 'permissive') {
            return true;
        }

        if ($user === null) {
            return false;
        }

        if ($defaultMode === 'gate-only') {
            return Gate::forUser($user)->check($gateAbility, $record);
        }

        // Default 'secure' mode:
        // If a policy or a gate exists, check it. Otherwise, allow authenticated users.
        if (Gate::getPolicyFor($record) || Gate::has($gateAbility)) {
            return Gate::forUser($user)->check($gateAbility, $record);
        }

        return true;
    }
}
