<?php

use Matheusmarnt\Scoutify\Support\TypeManifest;

beforeEach(function () {
    TypeManifest::forget();
});

afterEach(function () {
    TypeManifest::forget();
});

it('runs successfully and outputs rebuild header', function () {
    config(['scoutify.discovery.paths' => []]);

    $this->artisan('scoutify:rebuild')
        ->assertSuccessful()
        ->expectsOutputToContain('Scanning for Searchable models')
        ->expectsOutputToContain('Manifest rebuilt');
});

it('uses plural types label for zero count', function () {
    config(['scoutify.discovery.paths' => []]);

    $this->artisan('scoutify:rebuild')
        ->assertSuccessful()
        ->expectsOutputToContain('types registered');
});

it('uses singular type label when exactly one model discovered', function () {
    $dir = sys_get_temp_dir().'/scoutify-rebuild-'.uniqid();
    mkdir($dir);

    // Write a file whose FQCN tokenizes to the already-loaded Article fixture
    file_put_contents("$dir/Article.php", <<<'PHP'
<?php
namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;
use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;
class Article extends Model implements GloballySearchable
{
    use Searchable;
    public static function globalSearchGroup(): string { return 'articles'; }
    public static function globalSearchIcon(): string { return 'heroicon-o-document'; }
    public static function globalSearchColor(): string { return 'blue'; }
}
PHP);

    config(['scoutify.discovery.paths' => [$dir]]);

    $this->artisan('scoutify:rebuild')
        ->assertSuccessful()
        ->expectsOutputToContain('1 type registered');

    unlink("$dir/Article.php");
    rmdir($dir);
});

it('writes the manifest file to disk', function () {
    config(['scoutify.discovery.paths' => []]);

    $this->artisan('scoutify:rebuild')->assertSuccessful();

    expect(is_file(TypeManifest::path()))->toBeTrue();
});
