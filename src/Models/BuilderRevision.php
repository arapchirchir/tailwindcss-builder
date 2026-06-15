<?php

namespace Tecworld\TailwindBuilder\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BuilderRevision extends Model
{
    protected $guarded = [];

    protected $casts = [
        'content_json' => 'array',
        'snapshot_json' => 'array',
    ];

    public function getTable(): string
    {
        return config('tailwind-builder.tables.revisions', 'twb_revisions');
    }

    public function scopeForPage(Builder $query, int $pageId): Builder
    {
        return $query->where('revisionable_type', 'page')
            ->where('revisionable_id', $pageId);
    }

    public function scopeForComponent(Builder $query, int $componentId): Builder
    {
        return $query->where('revisionable_type', 'component')
            ->where('revisionable_id', $componentId);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->latest();
    }
}
