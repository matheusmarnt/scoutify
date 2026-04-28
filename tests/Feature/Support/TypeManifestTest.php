<?php

use Matheusmarnt\Scoutify\Support\TypeManifest;

beforeEach(function () {
    TypeManifest::forget();
});

afterEach(function () {
    TypeManifest::forget();
});

it('path returns bootstrap cache path', function () {
    $path = TypeManifest::path();

    expect($path)->toBe(app()->bootstrapPath('cache/scoutify-types.php'));
});

it('load returns empty array when manifest file does not exist', function () {
    expect(TypeManifest::load())->toBe([]);
});

it('write and load roundtrip preserves manifest', function () {
    $manifest = [
        'App\Models\User' => [
            'key' => 'User',
            'label' => 'Users',
            'icon' => 'heroicon-o-user',
            'color' => 'indigo',
        ],
    ];

    TypeManifest::write($manifest);

    expect(TypeManifest::load())->toBe($manifest);
});

it('forget removes the manifest file', function () {
    TypeManifest::write(['App\Models\User' => ['key' => 'User', 'label' => 'Users', 'icon' => 'x', 'color' => 'gray']]);

    expect(is_file(TypeManifest::path()))->toBeTrue();

    TypeManifest::forget();

    expect(is_file(TypeManifest::path()))->toBeFalse();
});

it('build returns empty array when no models directory exists', function () {
    config()->set('scoutify.discovery.paths', ['/tmp/scoutify-nonexistent-dir-'.uniqid()]);

    $manifest = TypeManifest::build();

    expect($manifest)->toBe([]);
});

it('build discovers model using Searchable trait', function () {
    // Create a temp dir with a fixture model file that uses Searchable
    $dir = sys_get_temp_dir().'/scoutify-test-'.uniqid();
    mkdir($dir);

    $modelClass = 'Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article';
    $file = $dir.'/Article.php';

    // Write a PHP file pointing at the already-loaded fixture class
    file_put_contents($file, <<<PHP
<?php
namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;

class Article extends Model implements GloballySearchable
{
    use Searchable;

    protected \$fillable = ['name', 'body'];

    public static function globalSearchGroup(): string { return 'articles'; }
    public static function globalSearchIcon(): string { return 'heroicon-o-document'; }
    public static function globalSearchColor(): string { return 'blue'; }
}
PHP);

    config()->set('scoutify.discovery.paths', [$dir]);

    // Since the class is already loaded (from fixture), build() should pick it up
    $manifest = TypeManifest::build();

    // Clean up
    unlink($file);
    rmdir($dir);

    expect($manifest)->toHaveKey($modelClass)
        ->and($manifest[$modelClass]['key'])->toBe('articles')
        ->and($manifest[$modelClass]['color'])->toBe('blue');
});
