<?php

use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('returns navigate for internal url (same host as app)', function () {
    $model = new class (['name' => 'Test']) extends Article {
        public function globalSearchUrl(): string
        {
            return url('/articles/1');
        }
    };

    expect($model->globalSearchLinkTarget())->toBe('navigate');
});

it('returns navigate for relative url (no host)', function () {
    $model = new class (['name' => 'Test']) extends Article {
        public function globalSearchUrl(): string
        {
            return '/articles/1';
        }
    };

    expect($model->globalSearchLinkTarget())->toBe('navigate');
});

it('returns _blank for external url (different host)', function () {
    $model = new class (['name' => 'Test']) extends Article {
        public function globalSearchUrl(): string
        {
            return 'https://falabr.cgu.gov.br/web/TO/Gurupi';
        }
    };

    expect($model->globalSearchLinkTarget())->toBe('_blank');
});

it('model can override globalSearchLinkTarget to return custom value', function () {
    $model = new class (['name' => 'Test']) extends Article {
        public function globalSearchLinkTarget(): string
        {
            return '_self';
        }
    };

    expect($model->globalSearchLinkTarget())->toBe('_self');
});
