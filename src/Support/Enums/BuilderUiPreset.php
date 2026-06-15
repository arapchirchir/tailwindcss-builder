<?php

namespace Tecworld\TailwindBuilder\Support\Enums;

final class BuilderUiPreset
{
    public const DAISYUI = 'daisyui';

    public const TAILWIND = 'tailwind';

    public const CUSTOM = 'custom';

    public static function values(): array
    {
        return [
            self::DAISYUI,
            self::TAILWIND,
            self::CUSTOM,
        ];
    }
}
