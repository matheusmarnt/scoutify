<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;
use Matheusmarnt\Scoutify\Contracts\HasGlobalSearchPreview;
use Matheusmarnt\Scoutify\Support\PreviewDto;

class ExternalPreviewArticle extends Model implements GloballySearchable, HasGlobalSearchPreview
{
    use Searchable;

    protected $table = 'previewable_articles';

    protected $fillable = ['name', 'file_disk', 'file_path'];

    public static function globalSearchGroup(): string
    {
        return 'external_previews';
    }

    public static function globalSearchIcon(): string
    {
        return 'heroicon-o-link';
    }

    public function globalSearchUrl(): string
    {
        return '/articles/' . $this->id;
    }

    public function globalSearchPreview(): ?PreviewDto
    {
        return PreviewDto::fromUrl(
            url: 'https://cdn.example.com/report.pdf',
            mime: 'application/pdf',
            filename: 'report.pdf',
        );
    }
}
