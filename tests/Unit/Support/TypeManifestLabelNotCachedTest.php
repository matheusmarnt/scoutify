<?php

use Matheusmarnt\Scoutify\Support\TypeManifest;

beforeEach(function () {
    TypeManifest::forget();
});

afterEach(function () {
    TypeManifest::forget();
});

it('build() result does not contain a label key for discovered models', function () {
    $dir = sys_get_temp_dir().'/scoutify-test-label-'.uniqid();
    mkdir($dir);

    $file = $dir.'/Article.php';
    file_put_contents($file, <<<'PHP'
<?php
namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;

class Article extends Model implements GloballySearchable
{
    use Searchable;

    protected $fillable = ['name', 'body'];

    public static function globalSearchGroup(): string { return 'articles'; }
    public static function globalSearchIcon(): string { return 'heroicon-o-document'; }
    public static function globalSearchColor(): string { return 'blue'; }
}
PHP);

    config()->set('scoutify.discovery.paths', [$dir]);

    $manifest = TypeManifest::build();

    unlink($file);
    rmdir($dir);

    $modelClass = 'Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article';

    expect($manifest)->toHaveKey($modelClass);
    expect($manifest[$modelClass])->not->toHaveKey('label');
});

it('load() returns entries without a label key when cache was written without label', function () {
    $stub = [
        'App\\Models\\Fake' => [
            'key' => 'fakes',
            'icon' => 'heroicon-o-user',
            'color' => 'blue',
        ],
    ];

    TypeManifest::write($stub);

    $loaded = TypeManifest::load();

    expect($loaded)->toHaveKey('App\\Models\\Fake');
    expect($loaded['App\\Models\\Fake'])->not->toHaveKey('label');
});
