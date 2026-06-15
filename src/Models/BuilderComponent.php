<?php

namespace Tecworld\TailwindBuilder\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tecworld\TailwindBuilder\Support\Enums\BuilderComponentStatus;

class BuilderComponent extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'content_json' => 'array',
        'settings_json' => 'array',
        'is_system' => 'boolean',
        'is_global' => 'boolean',
    ];

    public function getTable(): string
    {
        return config('tailwind-builder.tables.components', 'twb_components');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', BuilderComponentStatus::ACTIVE);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', BuilderComponentStatus::DRAFT);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', BuilderComponentStatus::ARCHIVED);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeForPreset(Builder $query, string $preset): Builder
    {
        return $query->where('ui_preset', $preset);
    }

    public function scopeSystem(Builder $query): Builder
    {
        return $query->where('is_system', true);
    }

    public function scopeGlobal(Builder $query): Builder
    {
        return $query->where('is_global', true);
    }
}
