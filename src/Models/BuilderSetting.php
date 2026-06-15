<?php

namespace Tecworld\TailwindBuilder\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BuilderSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'value' => 'array',
        'is_system' => 'boolean',
    ];

    public function getTable(): string
    {
        return config('tailwind-builder.tables.settings', 'twb_settings');
    }

    public function scopeGroup(Builder $query, string $group): Builder
    {
        return $query->where('group', $group);
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public static function setValue(string $key, mixed $value, string $group = 'general', bool $isSystem = false): self
    {
        return static::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'is_system' => $isSystem,
            ]
        );
    }
}
