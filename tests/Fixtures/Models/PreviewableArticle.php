<?php

namespace Matheusmarnt\Scoutify\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;
use Matheusmarnt\Scoutify\Contracts\HasGlobalSearchPreview;
use Matheusmarnt\Scoutify\Support\PreviewDto;

class PreviewableArticle extends Model implements GloballySearchable, HasGlobalSearchPreview
{
    use Searchable;

    protected $table = 'previewable_articles';

    protected $fillable = ['name', 'file_disk', 'file_path'];

    public static function globalSearchIcon(): string
    {
        return 'heroicon-o-document';
    }

    public function globalSearchUrl(): string
    {
        return '/articles/' . $this->id;
    }

    public function globalSearchPreview(): ?PreviewDto
    {
        if (empty($this->file_disk) || empty($this->file_path)) {
            return null;
        }

        return PreviewDto::fromDisk(
            disk: $this->file_disk,
            path: $this->file_path,
            mime: 'application/pdf',
            filename: basename($this->file_path),
        );
    }
}
