<?php

namespace Tecworld\TailwindBuilder\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class BuilderAsset extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'metadata_json' => 'array',
        'size' => 'integer',
    ];

    public function getTable(): string
    {
        return config('tailwind-builder.tables.assets', 'twb_assets');
    }

    public function scopeImages(Builder $query): Builder
    {
        return $query->where('type', 'image');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
