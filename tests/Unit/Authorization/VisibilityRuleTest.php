<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Matheusmarnt\Scoutify\Authorization\VisibilityMode;
use Matheusmarnt\Scoutify\Authorization\VisibilityRule;

it('can be instantiated via make', function () {
    $rule = VisibilityRule::make();
    expect($rule)->toBeInstanceOf(VisibilityRule::class);
});

it('defaults to hidden for guests and authenticated users', function () {
    $rule = VisibilityRule::make();
    $model = Mockery::mock(Model::class);
    $user = Mockery::mock(Authenticatable::class);

    expect($rule->evaluate($model, null))->toBeFalse();
    expect($rule->evaluate($model, $user))->toBeFalse();
});

it('can allow authenticated users with policy', function () {
    $rule = VisibilityRule::make()->whenAuthenticated()->policy('view');
    $model = Mockery::mock(Model::class);
    $user = Mockery::mock(Authenticatable::class);

    Gate::shouldReceive('forUser')->with($user)->andReturn($gate = Mockery::mock());
    $gate->shouldReceive('check')->with('view', $model)->andReturn(true);

    expect($rule->evaluate($model, $user))->toBeTrue();
});

it('can allow authenticated users with attribute', function () {
    $rule = VisibilityRule::make()->whenAuthenticated()->attribute('is_public');
    $model = new class extends Model
    {
        public $is_public;
    };
    $model->is_public = true;
    $user = Mockery::mock(Authenticatable::class);

    expect($rule->evaluate($model, $user))->toBeTrue();

    $model->is_public = false;
    expect($rule->evaluate($model, $user))->toBeFalse();
});

it('can allow authenticated users with custom closure', function () {
    $rule = VisibilityRule::make()->whenAuthenticated()->using(fn ($m, $u) => $m->id === 1);
    $model = new class extends Model
    {
        public $id;
    };
    $model->id = 1;
    $user = Mockery::mock(Authenticatable::class);

    expect($rule->evaluate($model, $user))->toBeTrue();

    $model->id = 2;
    expect($rule->evaluate($model, $user))->toBeFalse();
});

it('supports mode Any (default)', function () {
    $rule = VisibilityRule::make()
        ->whenAuthenticated()
        ->attribute('is_public')
        ->orAttribute('is_admin_only');

    $model = new class extends Model
    {
        public $is_public;

        public $is_admin_only;
    };
    $user = Mockery::mock(Authenticatable::class);

    $model->is_public = false;
    $model->is_admin_only = true;
    expect($rule->evaluate($model, $user))->toBeTrue();
});

it('supports mode All', function () {
    $rule = VisibilityRule::make()
        ->whenAuthenticated()
        ->mode(VisibilityMode::All)
        ->attribute('is_public')
        ->attribute('is_active');

    $model = new class extends Model
    {
        public $is_public;

        public $is_active;
    };
    $user = Mockery::mock(Authenticatable::class);

    $model->is_public = true;
    $model->is_active = false;
    expect($rule->evaluate($model, $user))->toBeFalse();

    $model->is_active = true;
    expect($rule->evaluate($model, $user))->toBeTrue();
});

it('can allow authenticated users with Spatie permission', function () {
    $rule = VisibilityRule::make()->whenAuthenticated()->permission('edit-articles');
    $model = Mockery::mock(Model::class);
    $user = Mockery::mock(new class implements Authenticatable
    {
        use Illuminate\Auth\Authenticatable;

        public function hasPermissionTo($permission, $guard = null)
        {
            return false;
        }

        public function hasAnyPermission($permissions, $guard = null)
        {
            return false;
        }
    });

    $user->shouldReceive('hasPermissionTo')->with('edit-articles', null)->andReturn(true);

    expect($rule->evaluate($model, $user))->toBeTrue();
});

it('can allow authenticated users with Spatie role', function () {
    $rule = VisibilityRule::make()->whenAuthenticated()->role('admin');
    $model = Mockery::mock(Model::class);
    $user = Mockery::mock(new class implements Authenticatable
    {
        use Illuminate\Auth\Authenticatable;

        public function hasRole($role, $guard = null)
        {
            return false;
        }

        public function hasAnyRole($roles, $guard = null)
        {
            return false;
        }
    });

    $user->shouldReceive('hasRole')->with('admin', null)->andReturn(true);

    expect($rule->evaluate($model, $user))->toBeTrue();
});

it('returns false if Spatie methods do not exist', function () {
    $rule = VisibilityRule::make()->whenAuthenticated()->permission('edit-articles');
    $model = Mockery::mock(Model::class);
    $user = Mockery::mock(Authenticatable::class);

    expect($rule->evaluate($model, $user))->toBeFalse();
});

it('uses hasAnyPermission when permission is passed as array', function () {
    $rule = VisibilityRule::make()->permission(['edit', 'view']);
    $model = Mockery::mock(Model::class);
    $user = Mockery::mock(new class implements Authenticatable
    {
        use Illuminate\Auth\Authenticatable;

        public function hasPermissionTo($permission, $guard = null): bool
        {
            return false;
        }

        public function hasAnyPermission($permissions, $guard = null): bool
        {
            return false;
        }
    });
    $user->shouldReceive('hasAnyPermission')->with(['edit', 'view'], null)->andReturn(true);

    expect($rule->evaluate($model, $user))->toBeTrue();
});

it('orPermission delegates to permission', function () {
    $rule = VisibilityRule::make()->orPermission('edit');
    $model = Mockery::mock(Model::class);
    $user = Mockery::mock(Authenticatable::class);

    expect($rule->evaluate($model, $user))->toBeFalse();
});

it('returns false when user lacks hasRole method', function () {
    $rule = VisibilityRule::make()->role('admin');
    $model = Mockery::mock(Model::class);
    $user = Mockery::mock(Authenticatable::class);

    expect($rule->evaluate($model, $user))->toBeFalse();
});

it('uses hasAnyRole when role is passed as array', function () {
    $rule = VisibilityRule::make()->role(['admin', 'editor']);
    $model = Mockery::mock(Model::class);
    $user = Mockery::mock(new class implements Authenticatable
    {
        use Illuminate\Auth\Authenticatable;

        public function hasRole($role, $guard = null): bool
        {
            return false;
        }

        public function hasAnyRole($roles, $guard = null): bool
        {
            return false;
        }
    });
    $user->shouldReceive('hasAnyRole')->with(['admin', 'editor'], null)->andReturn(true);

    expect($rule->evaluate($model, $user))->toBeTrue();
});

it('orRole delegates to role', function () {
    $rule = VisibilityRule::make()->orRole('admin');
    $model = Mockery::mock(Model::class);
    $user = Mockery::mock(Authenticatable::class);

    expect($rule->evaluate($model, $user))->toBeFalse();
});
